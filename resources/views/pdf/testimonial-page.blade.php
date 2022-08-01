<!DOCTYPE html><html><head><title>Testimonial Page</title></head><body><table border="0" cellspacing="5" cellpadding="1" style="text-align:center"> <tr> <td>{!! ($proposal_template->testimonials_title)? html_entity_decode($proposal_template->testimonials_title, ENT_QUOTES, 'UTF-8') : html_entity_decode('<h1 style="text-align:center">Testimonialss</h1>', ENT_QUOTES, 'UTF-8')!!}</td></tr><tr> <td>{!! ($proposal_template->testimonials_content)? html_entity_decode($proposal_template->testimonials_content, ENT_QUOTES, 'UTF-8') : html_entity_decode('<p><strong>This is pre-design or reference photo of your solar power plant.</strong></p>', ENT_QUOTES, 'UTF-8')!!}</td></tr></table><br><br><br><br><table border="0" cellspacing="0" cellpadding="4" style="border-bottom:0.5px solid #dee2e6;border-radius:50rem!important"> <tr> <td rowspan="4"><img src="{!! Storage::url($testimonials->image_one) !!}"/></td><td colspan="3"><p>{{$testimonials->description_one}}</p></td></tr><tr> <td colspan="3"></td></tr><tr> <td colspan="3"><strong>{{$testimonials->client_name_one}}</strong></td></tr><tr> <td colspan="3"> <table width="100"> <tr> @for($x=0;$x<$testimonials->rating_one;$x++) <td width="20"> <img src="{!! Storage::url('star.png') !!}" width="12">&nbsp; </td>@endfor </tr></table> </td></tr></table><br><br><table border="0" cellspacing="0" cellpadding="4" style="border-bottom:0.5px solid #dee2e6;border-radius:1rem!important"> <tr> <td colspan="3"><p>{{$testimonials->description_two}}</p></td><td rowspan="4"><img src="{!! Storage::url($testimonials->image_two) !!}" style="border-radius:50%"></td></tr><tr> <td colspan="3"></td></tr><tr> <td colspan="3"><strong>{{$testimonials->client_name_two}}</strong></td></tr><tr> <td colspan="3"> <table width="100"> <tr> @for($x=0;$x<$testimonials->rating_two;$x++) <td width="20"> <img src="{!! Storage::url('star.png') !!}" width="12">&nbsp; </td>@endfor </tr></table> </td></tr></table><br><br><table border="0" cellspacing="0" cellpadding="4" style="border-radius:50rem!important"> <tr> <td rowspan="4"><img src="{!! Storage::url($testimonials->image_three) !!}"/></td><td colspan="3"><p>{{$testimonials->description_three}}</p></td></tr><tr> <td colspan="3"></td></tr><tr> <td colspan="3"><strong>{{$testimonials->client_name_three}}</strong></td></tr><tr> <td colspan="3" width="100"> <table> <tr> @for($x=0;$x<$testimonials->rating_three;$x++) <td width="20"> <img src="{!! Storage::url('star.png') !!}" width="12">&nbsp; </td>@endfor </tr></table> </td></tr></table></body></html>