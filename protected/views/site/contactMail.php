<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Message reçu depuis le site Perunil 2</title>
        <style>
            /* -------------------------------------
                            GLOBAL
            ------------------------------------- */
            * {
                margin: 0;
                padding: 0;
                font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                font-size: 100%;
                line-height: 1.6;
            }

            img {
                max-width: 100%;
            }

            body {
                -webkit-font-smoothing: antialiased;
                -webkit-text-size-adjust: none;
                width: 100%!important;
                height: 100%;
            }


            /* -------------------------------------
                            BODY
            ------------------------------------- */
            table.body-wrap {
                width: 100%;
                padding: 20px;
            }

            table.body-wrap .container {
                border: 1px solid #f0f0f0;
            }
            
            table.td {
                border: 1px solid #f0f0f0;
            }


            /* -------------------------------------
                            FOOTER
            ------------------------------------- */
            table.footer-wrap {
                width: 100%;	
                clear: both!important;
            }

            .footer-wrap .container p {
                font-size: 12px;
                color: #666;

            }

            table.footer-wrap a {
                color: #999;
            }


            /* -------------------------------------
                            TYPOGRAPHY
            ------------------------------------- */
            h1, h2, h3 {
                font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
                line-height: 1.1;
                margin-bottom: 15px;
                color: #000;
                margin: 40px 0 10px;
                line-height: 1.2;
                font-weight: 200;
            }

            h1 {
                font-size: 36px;
            }
            h2 {
                font-size: 28px;
            }
            h3 {
                font-size: 22px;
            }

            p, ul, ol {
                margin-bottom: 10px;
                font-weight: normal;
                font-size: 14px;
            }

            ul li, ol li {
                margin-left: 5px;
                list-style-position: inside;
            }

            /* ---------------------------------------------------
                            RESPONSIVENESS
                            Nuke it from orbit. It's the only way to be sure.
            ------------------------------------------------------ */

            /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
            .container {
                display: block!important;
                max-width: 600px!important;
                margin: 0 auto!important; /* makes it centered */
                clear: both!important;
            }

            /* Set the padding on the td rather than the div for Outlook compatibility */
            .body-wrap .container {
                padding: 20px;
            }

            /* This should also be a block element, so that it will fill 100% of the .container */
            .content {
                max-width: 600px;
                margin: 0 auto;
                display: block;
            }

            /* Let's make sure tables in the content area are 100% wide */
            .content table {
                width: 100%;
            }

        </style>
    </head>

    <body bgcolor="#f6f6f6">

        <!-- body -->
        <table class="body-wrap">
            <tr>
                <td></td>
                <td class="container" bgcolor="#FFFFFF">

                    <!-- content -->
                    <div class="content">
                        <table>
                            <tr>
                                <td>
                                    <p>Un message a été envoyé depuis le formulaire de contact du site Perunil 2.</p>
                                    <p>Date : <?php echo date(DATE_RFC822); ?><br>
                                       Adresse ip : <?php echo $_SERVER['REMOTE_ADDR']; ?></p>

                                    <table>
                                        <tr>
                                            <th>Champ</th>
                                            <th>Valeur</th>
                                        </tr>
                                        <tr>
                                            <td>Nom</td>
                                            <td><?php echo $contactForm->name; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td><?php echo $contactForm->email; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Type d'erreur</td>
                                            <td><?php echo $contactForm->getErrorTypeStr(); ?></td>
                                        </tr>
                                        <tr>
                                            <td>Dernière URL consultée</td>
                                            <td><?php echo $contactForm->lasturl; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Lien manquant</td>
                                            <td><?php echo $contactForm->missinglink; ?></td>
                                        </tr>
                                        <tr>
                                            <td>Message</td>
                                            <td><?php echo $contactForm->body; ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- /content -->

                </td>
                <td></td>
            </tr>
        </table>
        <!-- /body -->

        <!-- footer -->
        <table class="footer-wrap">
            <tr>
                <td></td>
                <td class="container">

                    <!-- content -->
                    <div class="content">
                        <table>
                            <tr>
                                <td align="center">
                                    <p>Ceci est un mail envoyé automatiquement depuis Perunil 2.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <!-- /content -->

                </td>
                <td></td>
            </tr>
        </table>
        <!-- /footer -->

    </body>
</html>
