<?php

namespace App\Http\Controllers;

use App\Models\admin\ViewUserData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\ProposalTemplates;
use Auth;
use Elibyy\TCPDF\Facades\TCPDF;


class ProposalController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $data = ProposalTemplates::select(['id','template_name'])->where('company_id',$company_id)->paginate(10);
        return view('template.proposal.index',compact('data'));
    }

    public function create(){

        $user = Auth::user();
        $company_id = ($user->company_id)? $user->company_id : $user->id;
        $proposal_template = ProposalTemplates::where('company_id',$company_id)->first();

        return view('template.proposal.create',compact('proposal_template'));
    }

    public function newCreate(){

        $user = Auth::user();
        $company_id = ($user->company_id)? $user->company_id : $user->id;
        $proposal_template = ProposalTemplates::where('company_id',$company_id)->first();

        return view('template.proposal.new-create',compact('proposal_template'));
    }

    public function pdfPreview(){
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $proposal_template = ProposalTemplates::where('company_id',$company_id)->first();
        $company_data = ViewUserData::where("id", $company_id)->orderBy('id', 'ASC')->get()->first();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf::setHeaderCallback(function ($pdf) use ($proposal_template) {
//            if ($pdf->PageNo() > 1) {
                $image_file = public_path(Storage::url($proposal_template->header_logo));
                $pdf->Image($image_file, $proposal_template->header_logo_left, $proposal_template->header_logo_top, $proposal_template->header_logo_size, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
                $pdf->SetY(7);
                // Set font
                $pdf->SetFont('helvetica', 'B', 20);
                $pdf->setPageMark();
                /* $pdf->SetAlpha(0.1);
                 $img_file = public_path('storage/logo.png');
                 $pdf->Image($img_file, 50, 135, 100, '', 0, 0, '', false, 300, '', false, false, false);*/

                // Title
//                $pdf->Cell(0, 15, 'Heaven Designs Pvt Ltd.', 0, false, 'C', 0, '', 1, false, 'M', 'M');
//            $pdf->line(1, 20, 209, 20, array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'solid' => 1, 'color' => "#dee2e6"));
//            }
        });

        // Custom Footer
        $pdf::setFooterCallback(function ($pdf) use ($proposal_template) {
            if ($pdf->PageNo() > 1) {
                $footer = '<table cellpadding="6"><tr style="background-color:' . $proposal_template->theme_color_one . ';"><td><a href="https://heavendesigns.in" target="_blank" style="text-decoration: none;color:#fff;">Heaven Designs</a></td><td style="text-align: right;color:#fff;">Social Media Link</td></tr></table>';
                $pdf->SetY(-9.6);

            }else{
                $footer = '<table cellpadding="6"><tr style="background-color:' . $proposal_template->theme_color_two . ';"><td><a href="https://heavendesigns.in" target="_blank" style="text-decoration: none;color:#fff;">Heaven Designs</a></td><td style="text-align: right;color:#fff;">Social Media Link</td></tr></table>';
                $pdf->SetY(-9.6);
            }
            $pdf->writeHTML($footer, true, false, true, false, '');
        });

        $pdf::SetAuthor('Chetan Moradiya');
        $pdf::SetTitle('Quickest | PDf');
        $pdf::SetSubject('Quick Estimate');

        //First page
        $pdf::SetMargins(0, 0, 0, true);
        $pdf::SetFontSubsetting(false);
        $pdf::SetFontSize('12px');
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::SetAutoPageBreak(false);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf-setting.cover-page', compact('proposal_template'));
            $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Second page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, $proposal_template->page_top_margin, 7, false);
        $pdf::SetFontSubsetting(true);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf-setting.about-page', compact('proposal_template'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Third page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, $proposal_template->page_top_margin, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf-setting.product-page', compact('proposal_template'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Fourth page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, $proposal_template->page_top_margin, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf-setting.estimate-page', compact('proposal_template',));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Fifth page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, $proposal_template->page_top_margin, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 10);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf-setting.term-and-condition-page', compact('proposal_template'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Sixth page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, $proposal_template->page_top_margin, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf-setting.testimonial-page', compact('proposal_template'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Seven page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, 15.5, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf-setting.thank-you-page', compact('proposal_template','company_data'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::Output('quickest-sample-pdf.pdf', 'I');
    }

    public function show(){

    }

    public function edit(){

    }

    public function store(Request $request){
        $input = $request->all();

//        $id = Crypt::decrypt($input['id']);
        $validator = Validator::make($input, [
            'template_name' => 'required',
            'color_picker_one' => 'required',
            'color_picker_two' => 'required',
            'logo_dimension_one' => 'required',
            'logo_dimension_img' => 'required',
            'cover_title' => 'required',
            'cover_content' => 'required',
            'cover_footer_one' => 'required',
            'cover_footer_two' => 'required',
            'aboutas_title' => 'required',
            'aboutas_content' => 'required',
            'testimonials_title' => 'required',
            'testimonials_content' => 'required',
            'item_table_no' => 'required',
            'item_table_item' => 'required',
            'item_table_hsn' => 'required',
            'item_table_qty' => 'required',
            'item_table_rate' => 'required',
            'item_table_discount' => 'required',
            'item_table_cgst' => 'required',
            'item_table_sgst' => 'required',
            'item_table_igst' => 'required',
            'item_table_total' => 'required',
            'est_bank_label' => 'required',
            'est_bank_details' => 'required',
            'est_term_condition_lable' => 'required',
            'est_term_condition_details' => 'required',
            'est_signature_lable' => 'required',
            'header_logo' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'cover_img' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'aboutas_img' => 'required|image|mimes:jpeg,png,jpg|max:1024',
            'est_signature_img' => 'required|image|mimes:jpeg,png,jpg|max:1024',

        ]);
        if ($request->file('header_logo')) {
            $path = $request->file('header_logo')->store('public/template');
            $input['header_logo'] = $path;
        }

        if ($request->file('cover_img')) {
            $path = $request->file('cover_img')->store('public/template');
            $input['cover_img'] = $path;
        }

        if ($request->file('aboutas_img')) {
            $path = $request->file('aboutas_img')->store('public/template');
            $input['aboutas_img'] = $path;
        }

        if ($request->file('est_signature_img')) {
            $path = $request->file('est_signature_img')->store('public/template');
            $input['est_signature_img'] = $path;
        }
        if ($request->file('est_signature_img')) {
            $path = $request->file('est_signature_img')->store('public/template');
            $input['est_signature_img'] = $path;
        }

        if ($request->file('thank_you_img')) {
            $path = $request->file('thank_you_img')->store('public/template');
            $input['thank_you_img'] = $path;
        }

        $user = Auth::user();
        $company_id = ($user->company_id)? $user->company_id : $user->id;
        ProposalTemplates::updateOrCreate(['company_id' => $company_id],$input);
        return response()->json(['success' => 'Successfully saved...'], 201);
//        $unit = ProposalTemplates::create($input);
//        echo "<pre>";
//        print_r($request->file('est_signature_img'));
//        print_r($input);
    }

    public function update(){

    }

    public function destroy(){

    }
}
