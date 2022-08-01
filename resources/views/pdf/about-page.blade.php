<!DOCTYPE html>
<html>
<head>
    <title>About Page</title>
</head>
<body><br>
<table border="0" cellspacing="0" cellpadding="0" width="100%"> <tr> <td colspan="2" style="width: 100%;text-align: center;"><img src="{!! Storage::url($proposal_template->aboutas_img) !!}" height="400"/></td></tr></table><table border="0" cellspacing="" cellpadding="4"> <tr> <td></td><td rowspan="2" style="background-color:{!! $proposal_template->theme_color_one !!};">{!!html_entity_decode($estimate->est_aboutus_title, ENT_QUOTES, 'UTF-8')!!}</td></tr><tr> <td></td></tr></table><table border="0" cellspacing="0" cellpadding="6">{{--<tr> <td><h1 style="text-align:left;font-size: 25px;vertical-align: text-bottom;">About Heaven</h1></td></tr>--}}<tr> <td>{!! html_entity_decode($estimate->est_aboutus_content, ENT_QUOTES, 'UTF-8')!!}</td></tr></table></body></html>
