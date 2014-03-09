<?php

/**
 * Create new folder in tmp directory to hold the edited narrative and moving
 * edited narrative to it
 */
function move_dir_to_tmp($src, $id)
{
  $CI =& get_instance();
  $tmpPath = $CI->config->item('site_data_dir') . '/tmp/' . time() . '/';
  if (!is_dir($tmpPath))
  {
    mkdir($tmpPath, 0775, TRUE);
  }
  $tmpPath .= '/' . $id . '/';
  rename($src, $tmpPath);
  return $tmpPath;
}

/**
 * Deleting a folder recursively
 */
function delete_dir($path)
{
  $file_scan = scandir($path);
  foreach ($file_scan as $filecheck)
  {
    $file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
    if ($filecheck != '.' && $filecheck != '..')
    {
      if ($file_extension == '')
      {
        delete_dir($path . $filecheck . '/');
      }
      else
      {
        unlink($path . $filecheck);
      }
    }
  }
  rmdir($path);
}

function narrative_track_data($narrative_id)
{
  $CI =& get_instance();
  $path = $CI->config->item('site_data_dir') . '/' . $narrative_id;
  $xml_reader = simplexml_load_file($path . "/AudioTimes.xml");
  $paths = array('tracks' => array(), 'pictures' => array());
  $last_picture = '';

  foreach ($xml_reader->Narrative as $narrative)
  {
    $paths['tracks'][] = $CI->config->item('site_data_dir') . '/' . $narrative_id . '/' . $narrative->Mp3Name;

    if (strcmp($last_picture, $narrative->Image))
    {
      $last_picture = $narrative->Image;
      $paths['pictures'][] = $CI->config->item('site_data_dir') . '/' . $narrative_id . '/' . $last_picture;
    }
  }

  return $paths;
}

/**
 *  Gathering deleted files
 */
function narrative_deleted_track_data($narrative_id)
{
  $CI =& get_instance();
  $path = $CI->config->item('site_data_dir') . '/' . $narrative_id . '/deleted';

  //Finding audio and images and storing their name and paths
  if (!is_dir($path))
  {
    return FALSE;
  }
  $file_scan = scandir($path);
  $paths = array('tracks' => array(), 'pictures' => array());
  foreach ($file_scan as $filecheck)
  {
    $file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);

    if ($filecheck != '.' && $filecheck != '..')
    {
      if ($file_extension == 'mp3')
      {
        $paths['tracks'][] = $path . '/' . $filecheck;
      }
      // FIXME it is likely have some audio files not ending in mp3.
      else
      {
        $paths['pictures'][] = $path  . '/' .  $filecheck;
      }
    }
  }
  return $paths;
}

/**
 * Archiving the tracks that are meant to be archived
 */
function narrative_delete_tracks($tracks, $new_narrative_dir, $tracks_for_deletion = array())
{
  foreach ($tracks as $i => $track)
  {
    if (in_array(basename($track), $tracks_for_deletion))
    {
      $track_dest = $new_narrative_dir . 'deleted/' . basename($track);
    }
    else
    {
      $track_dest = $new_narrative_dir . basename($track);
    }
    rename($track, $track_dest);
  }
  return TRUE;
}

/**
 * Restoring the tracks that are meant to be restored
 */
function narrative_restore_tracks($tracks, $new_narrative_dir, $tracks_for_restoration = array())
{
  foreach ($tracks as $i => $track)
  {
    if (in_array(basename($track), $tracks_for_restoration))
    {
      $track_dest = $new_narrative_dir . basename($track);
    }
    else
    {
      $track_dest = $new_narrative_dir . 'deleted/' . basename($track);
    }
    rename($track, $track_dest);
  }
  return TRUE;
}

/**
 * Archiving the pics that are meant to be archived
 */
function narrative_delete_pictures($pictures, $new_narrative_dir, $pictures_for_deletion = array())
{
  foreach ($pictures as $i => $picture)
  {
    if (in_array(basename($picture), $pictures_for_deletion))
    {
      $picture_dest = $new_narrative_dir . 'deleted/' . basename($picture);
    }
    else
    {
      $picture_dest = $new_narrative_dir . basename($picture);
    }
    rename($picture, $picture_dest);
  }
}

/**
 * Restoring the pics that are meant to be restored
 */
function narrative_restore_pictures($pictures, $new_narrative_dir, $pictures_for_restoration = array())
{
  foreach ($pictures as $i => $picture)
  {
    if (in_array(basename($picture), $pictures_for_restoration))
    {
      $picture_dest = $new_narrative_dir . basename($picture);
    }
    else
    {
      $picture_dest = $new_narrative_dir . 'deleted/' . basename($picture);
    }
    rename($picture, $picture_dest);
  }
}

/**
 * Moving XML file to new folder
 */
function narrative_move_xml($id, $new_narrative_dir)
{
  $CI =& get_instance();
  $base_dir = $CI->config->item('site_data_dir') . '/' . $id . '/';
  $file_scan = scandir($base_dir);
  foreach ($file_scan as $filecheck)
  {
    $file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
    // Finding the XML file to be moved
    if ($file_extension == "xml" && $filecheck != 'AudioTimes.xml')
    {
      rename($base_dir . $filecheck, $new_narrative_dir . $filecheck);
    }
  }
}

/**
 *  Moving deleted files from old to new deleted directory
 */
function narrative_move_files($current_narrative_dir, $new_narrative_dir)
{
  $file_scan = scandir($current_narrative_dir);
  foreach ($file_scan as $filecheck)
  {
    if ($filecheck != '.' && $filecheck != '..' && !is_dir($current_narrative_dir . $filecheck))
    {
      rename($current_narrative_dir . $filecheck, $new_narrative_dir . $filecheck);
    }
  }
}

/**
 *  Handling error of disappearing jpg
 */
function narrative_purge_files($old_narrative_dir, $new_narrative_dir)
{
  $file_scan = scandir($old_narrative_dir);
  foreach ($file_scan as $filecheck)
  {
    $file_extension = pathinfo($filecheck, PATHINFO_EXTENSION);
    if ($file_extension == 'jpg')
    {
      rename($old_narrative_dir . $filecheck, $new_narrative_dir . $filecheck);
    }
  }
}
