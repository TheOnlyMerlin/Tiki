<?php

function prefs_auth_list() {
	return array(
		'auth_method' => array(
			'name' => tra('Authentication method'),
			'description' => tra('Multiple authentication backends are supported by Tikiwiki. The default value is to use the internal user database.'),
			'type' => 'list',
			'help' => 'Login+Authentication+Method',
			'perspective' => false,
			'options' => array(
				'tiki' => tra('Tiki'),
				'openid' => tra('Tiki and OpenID'),
				'pam' => tra('Tiki and PAM'),
				'ldap' => tra('Tiki and LDAP'),
				'cas' => tra('CAS (Central Authentication Service)'),
				'shib' => tra('Shibboleth'),
				'ws' => tra('Web Server'),
				'phpbb' => tra('phpBB'),
			),
		),
		'auth_token_access' => array(
			'name' => tra('Token Access'),
			'description' => tra('Allow to access the content with superior rights with the presentation of a token. The primary use of this authentication method is to grant temporary access to content to an external service.'),
			'perspective' => false,
			'type' => 'flag',
		),
		'auth_token_access_maxtimeout' => array(
			'name' => tra('Token Access Max Timeout'),
			'description' => tra('The maximum duration for which the generated tokens will be valid.'),
			'type' => 'text',
			'size' => 5,
			'perspective' => false,
			'filter' => 'digits',
		),
		'auth_phpbb_create_tiki' => array(
			'name' => tra('Create user if not in Tiki'),
			'type' => 'flag',
		),
		'auth_phpbb_skip_admin' => array(
			'name' => tra('Skip admin user'),
			'type' => 'flag',
		),
		'auth_phpbb_disable_tikionly' => array(
			'name' => tra("Disable Tiki users who doesn't have a phpBB login. (They could have been deleted)."),
			'type' => 'flag',
		),
		'auth_phpbb_version' => array(
			'name' => tra('phpBB Version'),
			'type' => 'list',
			'options' => array(
				'3' => tra('3'),
			),
		),
		'auth_phpbb_dbhost' => array(
			'name' => tra('phpBB Database Hostname'),
			'type' => 'text',
			'size' => 40,
		),
		'auth_phpbb_dbuser' => array(
			'name' => tra('phpBB Database Username'),
			'type' => 'text',
			'size' => 40,
		),
		'auth_phpbb_dbpasswd' => array(
			'name' => tra('phpBB Database Password'),
			'type' => 'text',
			'size' => 40,
		),
		'auth_phpbb_dbname' => array(
			'name' => tra('phpBB Database Name'),
			'type' => 'text',
			'size' => 40,
		),
		'auth_phpbb_table_prefix' => array(
			'name' => tra('phpBB Table Prefix'),
			'type' => 'text',
			'size' => 40,
		),
		'auth_ldap_permit_tiki_users' => array(
			'name' => tra('Use Tiki authentication for users created in tiki'),
			'type' => 'flag',
		),
		'auth_ldap_host' => array(
			'name' => tra('Host'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_port' => array(
			'name' => tra('Port'),
			'type' => 'text',
			'size' => 5,
			'filter' => 'digits',
		),
		'auth_ldap_debug' => array(
			'name' => tra('Write LDAP debug Information in Tiki Logs'),
			'type' => 'flag',
		),
		'auth_ldap_ssl' => array(
			'name' => tra('Use SSL (ldaps)'),
			'type' => 'flag',
		),
		'auth_ldap_starttls' => array(
			'name' => tra('Use TLS'),
			'type' => 'flag',
		),
		'auth_ldap_type' => array(
			'name' => tra('LDAP Bind Type'),
			'type' => 'list',
			'options' => array(
				'default' => tra('Default: Anonymous Bind'),
				'full' => tra('Full: userattr=username,UserDN,BaseDN'),
				'ol' => tra('OpenLDAP: userattr=username,BaseDN'),
				'ad' => tra('Active Directory (username@domain)'),
				'plain' => tra('Plain Username'),
			),
		),
		'auth_ldap_scope' => array(
			'name' => tra('Search scope'),
			'type' => 'list',
			'options' => array(
				'sub' => tra('Subtree'),
				'one' => tra('One level'),
				'base' => tra('Base object'),
			),
		),
		'auth_ldap_version' => array(
			'name' => tra('LDAP version'),
			'type' => 'text',
			'size' => 5,
		),
		'auth_ldap_basedn' => array(
			'name' => tra('Base DN'),
			'type' => 'text',
			'size' => 15,
		),
		'auth_ldap_userdn' => array(
			'name' => tra('User DN'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_userattr' => array(
			'name' => tra('User attribute'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_useroc' => array(
			'name' => tra('User OC'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_nameattr' => array(
			'name' => tra('Realname attribute'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_countryattr' => array(
			'name' => tra('Country attribute'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_emailattr' => array(
			'name' => tra('E-mail attribute'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_groupdn' => array(
			'name' => tra('Group DN'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_groupattr' => array(
			'name' => tra('Group attribute'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_groupdescattr' => array(
			'name' => tra('Group description attribute'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_groupoc' => array(
			'name' => tra('Group OC'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_memberattr' => array(
			'name' => tra('Member attribute'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_memberisdn' => array(
			'name' => tra('Member is DN'),
			'type' => 'flag',
		),
		'auth_ldap_usergroupattr' => array(
			'name' => tra('Group attribute'),
			'type' => 'text',
			'size' => 20,
		),
		'auth_ldap_groupgroupattr' => array(
			'name' => tra('Group attribute in group entry'),
			'type' => 'text',
			'size' => 20,
			'hint' => tra('(Leave this empty if the group name is already given in the user attribute)'),
		),
		'auth_ldap_adminuser' => array(
			'name' => tra('Admin user'),
			'type' => 'text',
			'size' => 15,
		),
		'auth_ldap_adminpass' => array(
			'name' => tra('Admin password'),
			'type' => 'password',
			'size' => 15,
		),
	);
}
