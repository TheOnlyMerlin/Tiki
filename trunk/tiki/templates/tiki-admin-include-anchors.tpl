<a href="tiki-admin.php?page=features" title="{tr}Features{/tr}" class="link"><img border="0"
   src="pics/large/boot.png" alt="{tr}Features{/tr}" /></a>
<a href="tiki-admin.php?page=general" title="{tr}General{/tr}" class="link"><img border="0"
   src="pics/large/icon-configuration.png" alt="{tr}General{/tr}" /></a>
<a href="tiki-admin.php?page=login" title="{tr}Login{/tr}" class="link"><img border="0"
   src="pics/large/stock_quit.png" alt="{tr}Login{/tr}" /></a>
{if $feature_wiki and $feature_wiki eq 'y'}
<a href="tiki-admin.php?page=wiki" title="{tr}Wiki{/tr}" class="link"><img border="0"
   src="pics/large/wikipages.png" alt="{tr}Wiki{/tr}" /></a>
{/if}
{if $feature_galleries and $feature_galleries eq 'y'}
<a href="tiki-admin.php?page=gal" title="{tr}Image Galleries{/tr}" class="link"><img border="0"
   src="pics/large/stock_select-color.png" alt="{tr}Image Galleries{/tr}" /></a>
{/if}
{if $feature_articles and $feature_articles eq 'y'}
<a href="tiki-admin.php?page=cms" title="{tr}Articles{/tr}" class="link"><img border="0"
   src="pics/large/stock_bold.png" alt="{tr}Articles{/tr}" /></a>
{/if}
{if $feature_blogs and $feature_blogs eq 'y'}
<a href="tiki-admin.php?page=blogs" title="{tr}Blogs{/tr}" class="link"><img border="0"
   src="pics/large/blogs.png" alt="{tr}Blogs{/tr}" /></a>
{/if}
{if $feature_forums and $feature_forums eq 'y'}
<a href="tiki-admin.php?page=forums" title="{tr}Forums{/tr}" class="link"><img border="0"
   src="pics/large/stock_index.png" alt="{tr}Forums{/tr}" /></a>
{/if}
{if $feature_directory and $feature_directory eq 'y'}
<a href="tiki-admin.php?page=directory" title="{tr}Directory{/tr}" class="link"><img border="0"
   src="pics/large/gnome-fs-server.png" alt="{tr}Directory{/tr}" /></a>
{/if}
{if $feature_file_galleries and $feature_file_galleries eq 'y'}
<a href="tiki-admin.php?page=fgal" title="{tr}File Galleries{/tr}" class="link"><img border="0"
   src="pics/large/file-manager.png" alt="{tr}File Galleries{/tr}" /></a>
{/if}
{if $feature_faqs and $feature_faqs eq 'y'}
<a href="tiki-admin.php?page=faqs" title="{tr}FAQs{/tr}" class="link"><img border="0"
   src="pics/large/stock_dialog_question.png" alt="{tr}FAQs{/tr}" /></a>
{/if}
{if $feature_maps and $feature_maps eq 'y'}
<a href="tiki-admin.php?page=maps" title="{tr}Maps{/tr}" class="link"><img border="0"
   src="pics/large/maps.png" alt="{tr}Maps{/tr}" /></a>
{/if}
{if $feature_trackers and $feature_trackers eq 'y'}
<a href="tiki-admin.php?page=trackers" title="{tr}Trackers{/tr}" class="link"><img border="0"
   src="pics/large/gnome-settings-font.png" alt="{tr}Trackers{/tr}" /></a>
{/if}
{if $feature_calendar and $feature_calendar eq 'y'}
<a href="tiki-admin.php?page=calendar" title="{tr}Calendar{/tr}" class="link"><img border="0"
   src="pics/large/date.png" alt="{tr}Calendar{/tr}" /></a>
{/if}
{if $feature_userfiles and $feature_userfiles eq 'y'}
<a href="tiki-admin.php?page=userfiles" title="{tr}User files{/tr}" class="link"><img border="0"
   src="pics/large/userfiles.png" alt="{tr}User files{/tr}" /></a>
{/if}
{if $feature_polls and $feature_polls eq 'y'}
<a href="tiki-admin.php?page=polls" title="{tr}Polls{/tr}" class="link"><img border="0"
   src="pics/large/stock_missing-image.png" alt="{tr}Polls{/tr}" /></a>
{/if}
{if $feature_search and $feature_search eq 'y'}
<a href="tiki-admin.php?page=search" title="{tr}Search{/tr}" class="link"><img border="0"
   src="pics/large/xfce4-appfinder.png" alt="{tr}Search{/tr}" /></a>
{/if}
{if $feature_webmail and $feature_webmail eq 'y'}
<a href="tiki-admin.php?page=webmail" title="{tr}Webmail{/tr}" class="link"><img border="0"
   src="pics/large/evolution.png" alt="{tr}Webmail{/tr}" /></a>
{/if}
{if true} {* no feature for RSS? *}
<a href="tiki-admin.php?page=rss" title="{tr}RSS{/tr}" class="link"><img border="0"
   src="pics/large/gnome-globe.png" alt="{tr}RSS{/tr}" /></a>
{/if}
{if $feature_score and $feature_score eq 'y'}
<a href="tiki-admin.php?page=score" title="{tr}Score{/tr}" class="link"><img border="0"
   src="pics/large/stock_about.png" alt="{tr}Score{/tr}" /></a>
{/if}
{if true} {* no feature for metatags? *}
<a href="tiki-admin.php?page=metatags" title="{tr}Meta Tags{/tr}" class="link"><img border="0"
   src="pics/large/metatags.png" alt="{tr}Meta Tags{/tr}" /></a>
{/if}
{if true} {* no feature for community? *}
<a href="tiki-admin.php?page=community" title="{tr}Community{/tr}" class="link"><img border="0"
   src="pics/large/users.png" alt="{tr}Community{/tr}" /></a>
{/if}
{if $feature_siteidentity and $feature_siteidentity eq 'y'}
<a href="tiki-admin.php?page=siteid" title="{tr}Site Identity{/tr}" class="link"><img border="0"
   src="pics/large/gnome-settings-background.png" alt="{tr}Site Identity{/tr}" /></a>
{/if}
{if $feature_intertiki and $feature_intertiki eq 'y'}
<a href="tiki-admin.php?page=intertiki" title="{tr}Intertiki{/tr}" class="link"><img border="0"
   src="pics/large/intertiki.png" alt="{tr}InterTiki{/tr}" /></a>
{/if}
{if $feature_freetags and $feature_freetags eq 'y'}
<a href="tiki-admin.php?page=freetags" title="{tr}Freetags{/tr}" class="link"><img border="0"
   src="pics/large/vcard.png" alt="{tr}Freetags{/tr}" /></a>
{/if}
{if $feature_gmap and $feature_gmap eq 'y'}
<a href="tiki-admin.php?page=gmap" title="{tr}Google Maps{/tr}" class="link"><img border="0"
   src="pics/large/google_maps.png" alt="{tr}Google Maps{/tr}" /></a>
{/if}
<a href="tiki-admin.php?page=i18n" title="{tr}i18n{/tr}" class="link"><img border="0"
   src="pics/large/i18n.png" alt="{tr}i18n{/tr}" /></a>
