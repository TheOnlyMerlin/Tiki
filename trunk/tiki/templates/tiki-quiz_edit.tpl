{* $Header: /cvsroot/tikiwiki/tiki/templates/tiki-quiz_edit.tpl,v 1.3 2004-05-11 20:13:38 ggeller Exp $ *}

{* Copyright (c) 2004 *}
{* All Rights Reserved. See copyright.txt for details and a complete list of authors. *}
{* Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details. *}

<!- tiki-quiz_edit.tpl start ->

<a class="pagetitle" href="tiki-quiz_edit.php?quizId={$quizId}">{tr}Edit quiz{/tr}: {$quiz_info.name}</a>
{if $feature_help}
&nbsp &nbsp &nbsp<a title="{tr}help{/tr}" href="http://tikiwiki.org/tiki-index.php?page=QuizQuestionImport" target="help"><img border="0" alt="{tr}Help{/tr}" src="img/icons/help.gif" /></a>
{/if}
<br /><br />
<a class="linkbut" href="tiki-list_quizzes.php">{tr}list quizzes{/tr}</a>
<a class="linkbut" href="tiki-quiz_stats.php">{tr}quiz stats{/tr}</a>
<a class="linkbut" href="tiki-quiz_stats_quiz.php?quizId={$quizId}">{tr}this quiz stats{/tr}</a>
<a class="linkbut" href="tiki-edit_quiz.php">{tr}admin quizzes{/tr}</a>
<a class="linkbut" href="tiki-quiz_edit.php?quizId={$quizId}?view=XML">{tr}View/Edit as XML{/tr}</a>
<br />
<br />

<form enctype="multipart/form-data" method="post" action="tiki-quiz_edit.php?quizId={$quiz_info.quizId}">
	<table class="normal">
		<tr>
			<td class="formcolor"><label for="quiz-name">Name:</label></td>
			<td class="formcolor"><input type="text" name="name" id="quiz-name" value="{$quiz_info.name|escape}" size="80"></td>
		</tr>
		<tr>
			<td class="formcolor">
				<label for="quiz-desc">Description:</label>
			</td>
			<td class="formcolor"><input type="text" name="description" id="quiz-desc" value="{$quiz_info.description|escape}" size="80"></td>
		</tr>

    {include file=categorize.tpl}

		<tr class="formcolor">
      <td>{tr}Publication Date{/tr}</td>
      <td>
				{html_select_date prefix="publish_" time=$publishDateSite start_year="-5" end_year="+10"} {tr}at{/tr} <span dir="ltr">{html_select_time prefix="publish_" time=$publishDateSite display_seconds=false}
&nbsp;{$siteTimeZone}</span>
			</td>
		</tr>
		<tr class="formcolor">
			<td>{tr}Expiration Date{/tr}</td><td>
				{html_select_date prefix="expire_" time=$expireDateSite start_year="-5" end_year="+10"} {tr}at{/tr} <span dir="ltr">{html_select_time prefix="expire_" time=$expireDateSite display_seconds=false}
&nbsp;{$siteTimeZone}</span>
			</td>
		</tr>

  </table>
	<table class="normal">


<tr>
  <td class="formcolor"><input type="checkbox" name="shuffleQuestions" id="shuffle-questions" {if $shuffleQuestions eq 'y'}checked="checked"{/if} /><label for="shuffle-questions">{tr}Shuffle questions{/tr}</td>
  <td class="formcolor"><input type="checkbox" name="shuffleAnswers" id="shuffle-answers" {if $shuffleAnswers eq 'y'}checked="checked"{/if} /><label for="shuffle-answers">{tr}Shuffle answers{/tr}</td>
</tr>

<tr>
  <td colspan="2" class="formcolor"><input type="checkbox" name="limitDisplay" id="quiz-display-limit" {if $limitDisplay eq 'y'}checked="checked"{/if} /><label for="quiz-display-limit">{tr}Limit questions displayed per page to {/tr}</label><select name="questionsPerPage" id="quiz-perpage">{html_options values=$qpp selected=$questionsPerPage output=$qpp}</select>{tr}&nbsp question(s).{/tr}</td>
</tr>

<tr><td colspan="2" class="formcolor"><input type="checkbox" name="canRepeat" id="quiz-repeat" {if $canRepeat eq 'y'}checked="checked"{/if} /><label for="quiz-repeat">{tr}Allow students to retake this quiz {/tr}
<label for="quiz-maxtime"></label><select name="timeLimit" id="quiz-maxtime">{html_options values=$repetitions selected=$repetitionLimit output=$repetitions}</select> {tr}times{/tr}</td>
</tr>

<tr>
  <td colspan = "2" class="formcolor"><input type="checkbox" name="timeLimited" id="quiz-timelimit" {if $timeLimited eq 'y'}checked="checked"{/if} /><label for="quiz-timelimit">{tr}Impose a time limit of {/tr}</label><label for="quiz-maxtime"></label><select name="timeLimit" id="quiz-maxtime">{html_options values=$mins selected=$timeLimit output=$mins}</select> {tr}minutes{/tr}</td>
</tr>

<tr>
<td  colspan="2" class="formcolor"><input type="checkbox" name="storeResults" id="quiz-results" {if $storeResults eq 'y'}checked="checked"{/if} /><label for="quiz-results">{tr}Allow students to store partial results and return to quiz.{/tr}</td>
</tr>

<tr><td class="formcolor"><input type="checkbox" name="immediateFeedback" id="immediate-feedback" {if $immediateFeedback eq 'y'}checked="checked"{/if} /><label for="immediate-feedback">{tr}Immediate feedback{/tr}</td>
<td class="formcolor"><input type="checkbox" name="showAnswers" id="show-answers" {if $showAnswers eq 'y'}checked="checked"{/if} /><label for="show-answers">{tr}Show correct answers{/tr}</td></tr>

<tr>
  <td class="formcolor"><input type="checkbox" name="storeResults" id="quiz-results" {if $storeResults eq 'y'}checked="checked"{/if} /><label for="quiz-results">{tr}Store quiz results{/tr}</td>
<td class="formcolor"><input type="checkbox" name="storeResults" id="quiz-results" {if $storeResults eq 'y'}checked="checked"{/if} /><label for="quiz-results">{tr}Solicit additional questions from students.{/tr}</td>
</tr>

  </table>
	<table class="normal">

		<tr>
			<td class="formcolor"><input type="checkbox" name="bPeerReview" id="bPeerReview" {if $bPeerReview eq 'y'}checked="checked"{/if} /><label for="bPeerReview">{tr}Use peer review system{/tr}</td>
			<td class="formcolor"><input type="checkbox" name="forum" id="forum" {if $forum eq 'y'}checked="checked"{/if} /><label for="forum">{tr}Link quiz to forum named: {/tr}<input type="text" name="forum-name" id="forum-name" value="{$quiz_info.nameForum|escape}" size="40"></td>
		</tr>

{*
		<tr>
			<td colspan = "2" class="formcolor"><input type="checkbox" name="bPeerReview" id="bPeerReview" {if $bPeerReview eq 'y'}checked="checked"{/if} /><label for="bPeerReview">{tr}Use peer review system{/tr}</td>
		</tr>

		<tr>
			<td colspan = "2" class="formcolor"><input type="checkbox" name="forum" id="forum" {if $forum eq 'y'}checked="checked"{/if} /><label for="forum">{tr}Link quiz to forum named: {/tr}<input type="text" name="forum-name" id="forum-name" value="{$quiz_info.nameForum|escape}" size="40"></td>
		</tr>
*}

  </table>
	<table class="normal">



  </table>
	<table class="normal">

    <tr>
      <td class="formcolor">
        {tr}Edit:{/tr}
      </td>
      <td class="formcolor">
        <textarea class="wikiedit" name="input_data" rows="10" cols="80" id='subheading' wrap="virtual" ></textarea>
      </td>
    </tr>
		<tr class="formcolor">
      <td class="formcolor">
      </td>
      <td class="formcolor">
				<input type="submit" class="wikiaction" name="preview" value="{tr}preview{/tr}" />
      </td>

    </tr>
		<tr class="formcolor">
      <td class="formcolor">
      </td>
      <td class="formcolor">
				<input type="submit" class="wikiaction" name="save" value="{tr}save{/tr}" /> <a class="link" href="tiki-index.php?page={$page|escape:"url"}">{tr}cancel edit{/tr}</a></td>
      </td>
    </tr>
  </table>
</form>

<!- tiki-quiz_edit end ->
