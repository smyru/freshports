<?php
// DO NOT EDIT THIS FILE.  USE THE ADMIN


// one-time-set settings
  $PHORUM['main_table']='forums';
  $PHORUM['dbtype']='postgresql65';

// Global Settings
  $PHORUM['started']=1;
  $PHORUM['DefaultDisplay']='90';
  $PHORUM['DefaultEmail']='webmaster@FreshPorts.org';
  $PHORUM['PhorumMailCode']='';
  $PHORUM['UseCookies']='1';
  $PHORUM['SortForums']='1';
  $PHORUM['ActiveForums']='2';
  $PHORUM['TimezoneOffset']='0';

  $PHORUM['forum_url']='http://' . $_SERVER["HTTP_HOST"] . '/phorum';
  $PHORUM['admin_url']='http://' . $_SERVER["HTTP_HOST"] . '/phorum/WoCKer921/index.php';

  $PHORUM['AllowAttachments']='0';
  $PHORUM['AttachmentDir']='';
  $PHORUM['AttachmentSizeLimit']='';
  $PHORUM['AttachmentFileTypes']='';
  $PHORUM['MaximumNumberAttachments']='0';

  $PHORUM['ext']='php';
  $PHORUM['forum_page']='index';
  $PHORUM['list_page']='list';
  $PHORUM['search_page']='search';
  $PHORUM['read_page']='read';
  $PHORUM['post_page']='post';
  $PHORUM['violation_page']='violation';
  $PHORUM['down_page']='down';
  $PHORUM['attach_page']='attach';
  $PHORUM['default_lang']='lang/english.php';
  $PHORUM['default_body_color']='#FFFFFF';
  $PHORUM['default_body_link_color']='#0000FF';
  $PHORUM['default_body_vlink_color']='#330000';
  $PHORUM['default_body_alink_color']='#FF0000';
  $PHORUM['default_table_width']='98%';
  $PHORUM['default_table_header_color']='#FFCC33';
  $PHORUM['default_table_header_font_color']='#000000';
  $PHORUM['default_table_body_color_1']='#FFFFFF';
  $PHORUM['default_table_body_font_color_1']='#000000';
  $PHORUM['default_table_body_color_2']='#FFFFFF';
  $PHORUM['default_table_body_font_color_2']='#000000';
  $PHORUM['default_nav_color']='#FFCC33';
  $PHORUM['default_nav_font_color']='#000000';

  // expand all the above into vars for legacy code.
  while(list($key, $value)=each($PHORUM)){
    $$key=$PHORUM[$key];
  }

  // database variables
  $PHORUM['DatabaseServer']='';
  $PHORUM['DatabaseName']='fpphorum';
  $PHORUM['DatabaseUser']='alvormar';
  $PHORUM['DatabasePassword']='';



?>