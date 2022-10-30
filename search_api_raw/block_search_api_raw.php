<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


class block_search_api_raw extends block_base
{

    public function init()
    {
        $this->title = get_string('pluginname', 'block_search_api_raw');
    }
    
    public function get_searchApiJson()
    {
        $curl_session = curl_init();
        curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_session, CURLOPT_URL, "https://www.googleapis.com/customsearch/v1?key=yourAPIKEY&cx=yourCX&q=Moodle+Blocks");
        $result = curl_exec($curl_session);
        curl_close($curl_session);
        $res = json_decode($result, true);
        $len = count($res['items']);
        $ret = "";
        for ($i = 0; $i < $len; $i++) {
            $ret .="<pre>";
            $ret .= print_r($res['items'][$i], true);
            $ret .= "</pre>";
            $ret .= "<hr>";
        }
        return $ret;
    }

    public function get_content()
    {

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->footer = '&copy'. ' .... '. date('Y');

        // Add logic here to define your template data or any other content.
        $data = $this->get_searchApiJson();
        $this->content->text = $data;

        return $this->content;
    }

    /**
     * Defines in which pages this block can be added.
     *
     * @return array of the pages where the block can be added.
     */
    public function applicable_formats()
    {
        return [
            'admin' => false,
            'site-index' => true,
            'course-view' => true,
            'mod' => false,
            'my' => true,
        ];
    }
}
