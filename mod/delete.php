<?php

  define('BASEPATH', 'Staff');
  require_once('../applications/wrapper.php');

  if( !$TANGO->perm->check('access_moderation') ) { redirect(SITE_URL); }//Checks if user has permission to create a thread.
  $TANGO->tpl->getTpl('page');

  $content    = '';

  if( $PGET->g('post') ) {

      $MYSQL->where('id', $PGET->g('post'));
      $query = $MYSQL->get('{prefix}forum_posts');

      if( !empty($query) ) {

          if( $query['0']['post_type'] == "1" ) {
            $MYSQL->where('origin_thread', $query['0']['id']);
            if( $MYSQL->delete('{prefix}forum_posts') ) {
              $MYSQL->where('id', $query['0']['id']);
              if( $MYSQL->delete('{prefix}forum_posts') ) {
                $content .= $TANGO->tpl->entity(
                  'success_notice',
                  'content',
                  $LANG['mod']['delete']['thread_deleted']
                );
              } else {
                $content .= $TANGO->tpl->entity(
                  'danger_notice',
                  'content',
                  $LANG['mod']['delete']['error_deleting']
                );
              }
            } else {
              $content .= $TANGO->tpl->entity(
                'danger_notice',
                'content',
                $LANG['mod']['delete']['error_deleting']
              );
            }
          } else {
            $MYSQL->where('id', $query['0']['id']);
            if( $MYSQL->delete('{prefix}forum_posts') ) {
              $content .= $TANGO->tpl->entity(
                'success_notice',
                'content',
                $LANG['mod']['delete']['post_deleted']
              );
            } else {
              $content .= $TANGO->tpl->entity(
                'danger_notice',
                'content',
                $LANG['mod']['delete']['error_deleting']
              );
            }
          }

      } else {
          redirect(SITE_URL);
      }

  } else {
      redirect(SITE_URL);
  }

  $TANGO->tpl->addParam(
      array(
          'page_title',
          'content'
      ),
      array(
          $LANG['mod']['delete']['delete'],
          $content
      )
  );

  echo $TANGO->tpl->output();

?>