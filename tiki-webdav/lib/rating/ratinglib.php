<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

class RatingLib extends TikiDb_Bridge
{
	/**
	 * Record a vote for the current user or anonymous visitor.
	 */
	function record_vote( $type, $objectId, $score, $time = null ) {
		$target = $this->get_current_user();
		return $this->record_user_vote( $target, $type, $objectId, $score, $time );
	}

	/**
	 * Obtain the last vote on the item by the user or anonymous visitor.
	 */
	function get_vote( $type, $objectId ) {
		$target = $this->get_current_user();
		return $this->get_user_vote( $target, $type, $objectId );
	}

	/**
	 * Collect the aggregate score of an item based on various arguments.
	 *
	 * @param $type string The object type
	 * @param $objectId int|string The object identifier
	 * @param $aggregate string The aggregate function to use (sum or avg)
	 * @param $params array Various other arguments to affect the result. All options
	 *                      are valid for both avg and sum. If no parameters are provided,
	 *                      aggregate will be performed on the entire history, for all visitors
	 *                      without limitations on voting frequency. Valid parameters are:
	 *                      - range : Number of seconds to look back for
	 *                      - ignore : 'anonymous' is the only valid value. 
	 *                                 Will make sure only registered users are considered.
	 *                      - keep : Only consider one vote per user. 'oldest' or 'latest'.
	 *                      - revote : If the user is allowed to vote multiple times, contains the
	 *                                 amount of seconds between votes. Requires keep parameter.
	 */
	function collect( $type, $objectId, $aggregate, array $params = array() ) {
		if( $aggregate != 'avg' && $aggregate != 'sum' ) {
			return false;
		}

		$token = $this->get_token( $type, $objectId );
		$joins = array( '`tiki_user_votings` `uv`' );
		$where = array( '( `id` = ? )' );
		$bindvars = array( $token );

		if( isset( $params['range'] ) ) {
			$where[] = '( `time` > ? )';
			$bindvars[] = time() - abs( $params['range'] );
		}

		if( isset( $params['ignore'] ) && $params['ignore'] == 'anonymous' ) {
			$where[] = '( `user` NOT LIKE ? )';
			$bindvars[] = "anonymous\0%";
		}

		if( isset( $params['keep'] ) ) {
			if( $params['keep'] == 'latest' ) {
				$connect = 'MAX';
			} elseif( $params['keep'] == 'oldest' ) {
				$connect = 'MIN';
			}

			if( $connect ) {
				$extra = '';
				if( isset( $params['revote'] ) ) {
					$revote = max( 1, abs( $params['revote'] ) );
					$extra = " , FLOOR( ( UNIX_TIMESTAMP() - `time` ) / $revote )";
				}
				$joins[] = '
					INNER JOIN ( SELECT ' . $connect . '(`time`) `t`, `user` `u` FROM `tiki_user_votings` WHERE ' . implode( ' AND ', $where ) . ' GROUP BY `user` ' . $extra . ' ) `j`
						ON `j`.`u` = `uv`.`user` AND `j`.`t` = `uv`.`time`';
				$bindvars = array_merge( $bindvars, $bindvars );
			}
		}


		$query = 'SELECT '. $aggregate . '(`uv`.`optionId`) FROM ' . implode( ' ', $joins ) . ' WHERE ' . implode( ' AND ', $where );

		return (double) $this->getOne( $query, $bindvars );
	}

	function get_token( $type, $objectId ) {
		switch( $type ) {
		case 'article':
			return "article$objectId";
		case 'comment':
			return "comment$objectId";
		case 'wiki page':
			if( is_numeric( $objectId ) ) {
				return "wiki$objectId";
			}

			break;
		case 'test':
			return "test.$objectId";
		}

		return null;
	}

	function record_user_vote( $user, $type, $objectId, $score, $time = null ) {
		global $tikilib;

		if( ! $this->is_valid( $type, $score ) ) {
			return false;
		}

		if( is_null( $time ) ) {
			$time = time();
		}

		$ip = $tikilib->get_ip_address();
		$token = $this->get_token( $type, $objectId );

		if( is_null( $token ) ) {
			return false;
		}

		$this->query( 'INSERT INTO `tiki_user_votings` ( `user`, `ip`, `id`, `optionId`, `time` ) VALUES( ?, ?, ?, ?, ? )',
			array( $user, $ip, $token, $score, $time ) );

		return true;
	}

	function record_anonymous_vote( $sessionId, $type, $objectId, $score, $time = null ) {
		return $this->record_user_vote( $this->session_to_user( $sessionId ), $type, $objectId, $score, $time );
	}

	function is_valid( $type, $value ) {
		$options = $this->get_options( $type );

		return in_array( $value, $options );
	}

	function get_options( $type ) {
		$pref = 'rating_default_options';

		switch( $type ) {
		case 'wiki page':
			$pref = 'wiki_simple_ratings_options';
			break;
		case 'article':
			$pref = 'article_user_rating_options';
			break;
		}

		global $tikilib;
		return $tikilib->get_preference( $pref, range( 1, 5 ), true );
	}

	function get_user_vote( $user, $type, $objectId ) {
		$result = $this->fetchAll( 'SELECT `optionId` FROM `tiki_user_votings` WHERE `user` = ? AND `id` = ? ORDER BY `time` DESC',
			array( $user, $this->get_token( $type, $objectId ) ), 1 );

		if( count( $result ) == 1 ) {
			return (float) $result[0]['optionId'];
		}
	}

	function get_anonymous_vote( $sessionId, $type, $objectId ) {
		return $this->get_user_vote( $this->session_to_user( $sessionId ), $type, $objectId );
	}

	private function session_to_user( $sessionId ) {
		return "anonymous\0$sessionId";
	}

	private function get_current_user() {
		global $user;

		if( $user ) {
			return $user;
		} else {
			return $this->session_to_user( session_id() );
		}
	}
}

global $ratinglib; $ratinglib = new RatingLib;

