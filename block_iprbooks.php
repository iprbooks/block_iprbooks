<?php

class block_iprbooks extends block_base
{
    public function init()
    {
        $this->title = get_string('iprbooks', 'block_iprbooks');
    }

    public function get_content()
    {
        global $CFG;
        if ($this->content !== null) {
            return $this->content;
        }

        $style = file_get_contents($CFG->dirroot . "/blocks/iprbooks/style/iprbooks.css");
        $js = file_get_contents($CFG->dirroot . "/blocks/iprbooks/js/iprbooks.js");
        $mainPage = file_get_contents($CFG->dirroot . "/blocks/iprbooks/templates/rendermainpage.mustache");

        $this->content = new stdClass;
        $this->content->text .= "<style>" . $style . "</style>";
        $this->content->text .= "<script src=\"https://code.jquery.com/jquery-1.9.1.min.js\"></script>";
        $this->content->text .= $mainPage;
        $this->content->text .= "<script type=\"text/javascript\"> " . $js . " </script>";


        return $this->content;
    }

    public function hide_header()
    {
        return true;
    }

    function has_config()
    {
        return true;
    }

}
