<?php


require_once($CFG->dirroot . "/blog/renderer.php");

/**
 * Blog renderer
 */
class theme_purity_core_blog_renderer extends core_blog_renderer {

    /**
     * Renders a blog entry
     *
     * @param blog_entry $entry
     * @return string The table HTML
     */
    public function render_blog_entry(blog_entry $entry) {

        global $CFG;

        $syscontext = context_system::instance();

        $stredit = '<i class="fa fa-pencil mr-2" aria-hidden="true"></i>' . get_string('edit');
        $strdelete = '<i class="fa fa-trash mr-2" aria-hidden="true"></i>' . get_string('delete');

        // Header.
        $mainclass = 'forumpost blog_entry blog clearfix ';
        if ($entry->renderable->unassociatedentry) {
            $mainclass .= 'draft';
        } else {
            $mainclass .= $entry->publishstate;
        }

        $o = $this->output->container_start($mainclass, 'b' . $entry->id);
        // $o .= $this->output->container_start('row header clearfix');

        // // User picture.
        // $o .= $this->output->container_start('left picture header');
        // $o .= $this->output->user_picture($entry->renderable->user);
        // $o .= $this->output->container_end();

        // $o .= $this->output->container_start('topic starter header clearfix');

        $blogheader = $this->output->container_start('blog-title-info-container');

        // Title.
        $titlelink = html_writer::link(new moodle_url('/blog/index.php',
                                                       array('entryid' => $entry->id)),
                                                       format_string($entry->subject));
        // $o .= $this->output->container($titlelink, 'subject');

        $blogheader .= '<h3 class="h2 blog-title">' . $titlelink . '</h3>';

        // Post by.
        $by = new stdClass();
        $fullname = fullname($entry->renderable->user, has_capability('moodle/site:viewfullnames', $syscontext));
        $userurlparams = array('id' => $entry->renderable->user->id, 'course' => $this->page->course->id);
        $by->name = html_writer::link(new moodle_url('/user/view.php', $userurlparams), $fullname);
        $by->date = userdate($entry->created);

        // Comments count.
        if ($entry->renderable->comment) {
            $commentscount = $entry->renderable->comment->count();
        } else {
            $commentscount = '';
        }

        $blogauthor = '<dd class="blog-author" data-toggle="tooltip" title="' . get_string('written_by', 'theme_purity') . '"><i class="fa fa-user fa-fw icon" aria-hidden="true"></i>' . $by->name . '</dd>';
        $blogdate = '<dd class="blog-date" data-toggle="tooltip" title="' . get_string('created_date', 'theme_purity') . '"><i class="fa fa-calendar fa-fw icon" aria-hidden="true"></i>' . $by->date . '</dd>';
        $blogcomments = '<dd class="blog-comments" data-toggle="tooltip" title="' . get_string('blog_comments', 'theme_purity') . '"><i class="fa fa-commenting fa-fw icon" aria-hidden="true"></i>' . $commentscount . '</dd>';

        $blogheader .= html_writer::start_tag('dl', array('class'=>'blog-info'));;
        $blogheader .= $blogauthor;
        $blogheader .= $blogdate;

        if (!empty($entry->renderable->comment)) {
          $blogheader .= $blogcomments;
        }

        $blogheader .= html_writer::end_tag('dl');

        // Adding external blog link.
        if (!empty($entry->renderable->externalblogtext)) {
            $blogheader .= $this->output->container($entry->renderable->externalblogtext, 'externalblog');
        }

        $blogheader .= '<hr>';

        $blogheader .= $this->output->container_end();

        // Closing subject tag and header tag.
        // $o .= $this->output->container_end();
        // $o .= $this->output->container_end();

        // Post content.
        $o .= $this->output->container_start('row maincontent clearfix');

        // Entry.
        $o .= $this->output->container_start('blog-entry');

        // Determine text for publish state.
        switch ($entry->publishstate) {
            case 'draft':
                $blogtype = get_string('publishtonoone', 'blog');
                break;
            case 'site':
                $blogtype = get_string('publishtosite', 'blog');
                break;
            case 'public':
                $blogtype = get_string('publishtoworld', 'blog');
                break;
            default:
                $blogtype = '';
                break;

        }
        // $o .= $this->output->container($blogtype, 'audience');
        $publishto = $this->output->container($blogtype, 'audience');

        // Attachments.
        $attachmentsoutputs = array();
        if ($entry->renderable->attachments) {
            foreach ($entry->renderable->attachments as $attachment) {
                $o .= $this->render($attachment, false);
            }
        }

        $o .= $blogheader;

        // Body.
        $o .= format_text($entry->summary, $entry->summaryformat, array('overflowdiv' => true));

        if (!empty($entry->uniquehash)) {
            // Uniquehash is used as a link to an external blog.
            $url = clean_param($entry->uniquehash, PARAM_URL);
            if (!empty($url)) {
                $o .= $this->output->container_start('externalblog');
                $o .= html_writer::link($url, get_string('linktooriginalentry', 'blog'));
                $o .= $this->output->container_end();
            }
        }

        // Last modification.
        if ($entry->created != $entry->lastmodified) {
            // $o .= $this->output->container(' [ '.get_string('modified').': '.userdate($entry->lastmodified).' ]');
            $o .= '<div class="blog-modified-date">' . get_string('modified') .': ' . userdate($entry->lastmodified) . '</div>';
        }

        // Bottom wrapper
        $o .= $this->output->container_start('blog-bottom-container');

        // Links to tags.
        $o .= $this->output->tag_list(core_tag_tag::get_item_tags('core', 'post', $entry->id));

        // Available to
        $o .= '<hr><div class="blog-available-to">' . $publishto . '</div>';

        // Add associations.
        if (!empty($CFG->useblogassociations) && !empty($entry->renderable->blogassociations)) {

            // First find and show the associated course.
            $assocstr = '';
            $coursesarray = array();
            foreach ($entry->renderable->blogassociations as $assocrec) {
                if ($assocrec->contextlevel == CONTEXT_COURSE) {
                    $coursesarray[] = $this->output->action_icon($assocrec->url, $assocrec->icon, null, array(), true);
                }
            }
            if (!empty($coursesarray)) {
                $assocstr .= get_string('associated', 'blog', get_string('course')) . ': ' . implode(', ', $coursesarray);
            }

            // Now show mod association.
            $modulesarray = array();
            foreach ($entry->renderable->blogassociations as $assocrec) {
                if ($assocrec->contextlevel == CONTEXT_MODULE) {
                    $str = get_string('associated', 'blog', $assocrec->type) . ': ';
                    $str .= $this->output->action_icon($assocrec->url, $assocrec->icon, null, array(), true);
                    $modulesarray[] = $str;
                }
            }
            if (!empty($modulesarray)) {
                if (!empty($coursesarray)) {
                    $assocstr .= '<br/>';
                }
                $assocstr .= implode('<br/>', $modulesarray);
            }

            // Adding the asociations to the output.
            $o .= $this->output->container($assocstr, 'tags');
        }

        if ($entry->renderable->unassociatedentry) {
            $o .= $this->output->container(get_string('associationunviewable', 'blog'), 'noticebox');
        }

        // Commands.
        $o .= $this->output->container_start('commands');
        $o .= '<hr>';

        $o .= '<div class="commands-inner d-flex flex-wrap justify-content-start align-items-center">';
        if ($entry->renderable->usercanedit) {

            // External blog entries should not be edited.
            if (empty($entry->uniquehash)) {
                $o .= '<div class="pr-3 mr-3 border-right">' . html_writer::link(new moodle_url('/blog/edit.php',
                                                        array('action' => 'edit', 'entryid' => $entry->id)),
                                                        $stredit, array('class' => 'btn btn-secondary btn-sm')) . '</div>';
            }
            $o .= '<div class="pr-3 mr-3 border-right">' . html_writer::link(new moodle_url('/blog/edit.php',
                                                    array('action' => 'delete', 'entryid' => $entry->id)),
                                                    $strdelete, array('class' => 'btn btn-secondary btn-sm')) . '</div>';
        }

        $entryurl = new moodle_url('/blog/index.php', array('entryid' => $entry->id));
        $o .= html_writer::link($entryurl, get_string('permalink', 'blog'), array('class' => 'btn btn-secondary btn-sm'));
        $o .= '</div>';

        $o .= $this->output->container_end();

        // Comments.
        if (!empty($entry->renderable->comment)) {
            $o .= '<hr>';
            $o .= $entry->renderable->comment->output(true);
        }

        $o .= $this->output->container_end();

        // Closing bottom wrap
        $o .= $this->output->container_end();

        // Closing maincontent div.
        $o .= $this->output->container('&nbsp;', 'side options');
        $o .= $this->output->container_end();

        $o .= $this->output->container_end();

        return $o;
    }

}
