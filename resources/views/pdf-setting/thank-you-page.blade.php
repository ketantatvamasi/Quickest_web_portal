<!DOCTYPE html><html><head><title>Thank you Page</title></head><body><br>
<table border="0" cellspacing="0" cellpadding="4">
    <tr>
        <td colspan="3" style="text-align:center"><img
                src="{!! Storage::url($proposal_template->thank_you_img) !!}" style="width:350vh"></td>
    </tr>
    <tr>
        <td colspan="3" style="text-align:center">
            {{--<h1 style="text-align:left;font-size:25px;text-align:center">Thank You!</h1>--}}
        </td>
    </tr>
    <tr><br><br>
        <td style="border-right:.5px solid #dee2e6;text-align:center"><h3 style="text-transform:uppercase">{{$company_data->company_name}}</h3>
        </td>
        <td style="border-right:.5px solid #dee2e6;text-align:center"><h3 style="text-transform:uppercase">Mobile</h3>
        </td>
        <td style="text-align:center"><h3 style="text-transform:uppercase">Email</h3></td>
    </tr>
    <tr>
        <td style="border-right:.5px solid #dee2e6;text-align:center"><p>{{$company_data->address.','.$company_data->city_name.','.$company_data->state_name.','.$company_data->pincode}}</p></td>
        <td style="border-right:.5px solid #dee2e6;text-align:center"><p>{{$company_data->mobile_no}}</p></td>
        <td style="text-align:center"><p>{{$company_data->email}}</p></td>
    </tr>
</table></body></html>
