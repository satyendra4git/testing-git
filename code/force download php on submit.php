<form method="post" action="">
                                    <input type="hidden" name="record_id" value="<?php echo $resrow->id;?>">
                                  <button data-id="<?php echo $resrow->id;?>" name="rev_download" type="submit" class="btn download_button" id="">Download</button>
                               </form>
<?php
/** Note:- use hook for admin or simple call the function in functions.php file for frontend*/
add_action('admin_init', 'hdl_revision_download_script');
            function hdl_revision_download_script(){
                /** revision download script */
                if(isset($_POST['rev_download'])){
                    //echo "working..";
                    function force_download() {
                    $filedata = "This is sample content";
                    // SUCCESS
                    if ($filedata)
                    {
                        // GET A NAME FOR THE FILE
                        //$basename = basename($filename);
                        // THESE HEADERS ARE USED ON ALL BROWSERS
                        header("Content-Type: application-x/force-download");
                        header("Content-Disposition: attachment; filename=ads.txt");
                        header("Content-length: " . (string)(strlen($filedata)));
                        header("Expires: ".gmdate("D, d M Y H:i:s", mktime(date("H")+2, date("i"), date("s"), date("m"), date("d"), date("Y")))." GMT");
                        header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
                        // THIS HEADER MUST BE OMITTED FOR IE 6+
                        if (FALSE === strpos($_SERVER["HTTP_USER_AGENT"], 'MSIE '))
                        {
                            header("Cache-Control: no-cache, must-revalidate");
                        }
                        // THIS IS THE LAST HEADER
                        header("Pragma: no-cache");
                
                        // FLUSH THE HEADERS TO THE BROWSER
                        flush();
                        // CAPTURE THE FILE IN THE OUTPUT BUFFERS - WILL BE FLUSHED AT SCRIPT END
                        ob_start();
                        echo $filedata;
                    }
                    // FAILURE
                    else
                    {
                        die("ERROR: UNABLE TO OPEN $filename");
                    }
                }
                force_download();
                }

            }