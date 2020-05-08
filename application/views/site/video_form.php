<div>
    <script type="text/javascript">

        jQuery(document).ready(function($)
        {
            $('#dropzone form').toggleClass("dropzone").dropzone({
                url: "/site/handle_video_upload",
                maxFiles: 3,
                acceptedFiles: "video/*",
                capture: "camcorder",
                params: function() {
                    return {
                        "<?=PERSON_FIRST_NAME?>" : $('#<?=PERSON_FIRST_NAME?>').val(),
                        "<?=PERSON_LAST_NAME?>" : $('#<?=PERSON_LAST_NAME?>').val(),
                    };
                }
            });

        });

    </script>
</div>



