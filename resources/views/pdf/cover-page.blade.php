<!DOCTYPE html>
<html>
<head><title>Generate Laravel TCPDF by codeanddeploy.com</title></head>
<body><table border="0" cellspacing="0" cellpadding="0"> <tr> <td rowspan="3" style="background-color:{!! $proposal_template->theme_color_one !!};text-align:center;z-index:-9"><img src="{!! Storage::url($proposal_template->cover_img) !!}" height="832vh" width="304vh"></td><td style="background-color:{!! $proposal_template->theme_color_one !!}"> <table border="0" cellspacing="6" cellpadding="4" style="text-align:right;vertical-align:middle;padding-bottom:40px;" width="100%"> <tr> <td><div>{!! html_entity_decode($estimate->est_cover_page_title_div, ENT_COMPAT|ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401|ENT_NOQUOTES, 'UTF-8')!!}</div></td></tr></table> </td></tr><tr> <td style="background-color:{!! $proposal_template->theme_color_one !!}"><br><br><br><br><br><table border="0" cellspacing="0" cellpadding="4" style="padding-bottom:40px"> <tr> <td style="background-color:{!! $proposal_template->theme_color_two !!}"> <div>{!!html_entity_decode($estimate->est_cover_page_content_div, ENT_QUOTES, 'UTF-8')!!}</div></td></tr></table> </td></tr><tr> <td valign="bottom" style="background-color:{!! $proposal_template->theme_color_one !!}"><br><br><br><br><br><br><br><br><br><br><br><br><br><table border="0" cellspacing="2" cellpadding="5" style="text-align:right;width:100%"> <tr> <td colspan="3">{!! html_entity_decode($estimate->est_cover_page_footer_one_div, ENT_QUOTES, 'UTF-8')!!}</td></tr><tr> <td></td><td></td><td> <hr style="height:6px;color:#FFF"> </td></tr><tr> <td colspan="3">{!! html_entity_decode($estimate->est_cover_page_footer_two_div, ENT_QUOTES, 'UTF-8')!!}</td></tr></table> </td></tr></table>
</body>
</html>
