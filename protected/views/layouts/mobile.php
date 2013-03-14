<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="fr" />
        <meta name="viewport" content="width=default-width, initial-scale=1" />

        <!-- jquery mobile framework -->
        <link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.css" />
        <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
        <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
        <script src="http://code.jquery.com/mobile/1.3.0/jquery.mobile-1.3.0.min.js"></script>

        <title><?php echo CHtml::encode($this->pageTitle); ?></title>

    </head>
    <body>
        <div data-role=page id="mobilepage">

            <div data-role=header id="mobileheader">

                <h1>PérUnil</h1>

            </div>


            <div data-role=content id="mobilecontent">

                <?php echo $content; ?>

            </div>

        </div>
    </body>
</html>
