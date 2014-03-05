<?php

/**
 * @group Controller
 */

class Ajax_Test extends CIUnit_TestCase
{
    public function setUp()
    {
        // Set the tested controller
        $this->CI = set_controller('ajax');
    }

    public function test__audio_image__valid_folder()
    {
        // Call the controllers method
        $this->CI->audio_image(3, 1);

        // Fetch the buffered output
        $out = output();

        $prefix = base_url() . $this->CI->config->item('site_data_dir') . '/' . $first_id . '/';
        $matches = array();
        preg_match("|^(" . $prefix . ")(\d+).jpg$|", $out, $matches);

        // Check if the content is OK
        $this->assertEquals(3, count($matches));
    }
}
