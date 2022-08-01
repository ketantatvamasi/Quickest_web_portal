<?php

namespace App\Http\Controllers;

//use PDF;
use App\Models\admin\ViewUserData;
use App\Models\Estimate;
use App\Models\EstimateItems;
use App\Models\Product;
use App\Models\ProposalTemplates;
use App\Models\Testimonial;
use Elibyy\TCPDF\Facades\TCPDF;
use Illuminate\Support\Facades\Auth;

class PdfController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company_id = ($user->company_id) ? $user->company_id : $user->id;
        $estimate = Estimate::where([["id", 9], ["company_id", "=", $company_id]])->get()->first();
        $estimate_items = EstimateItems::where([["estimate_id", 9], ["company_id", "=", $company_id]])->orderBy('id', 'ASC')->get(["*"]);
        $proposal_template = ProposalTemplates::where('company_id', $company_id)->first();
        $company_data = ViewUserData::where("id", $company_id)->orderBy('id', 'ASC')->get()->first();
        $products = Product::select(["name", "id", "image_one", "image_two", "image_three"])->where('status', '=', 0)->whereIn('id', explode(',', $estimate->product_id))->where('company_id', $company_id)->get();


        $testimonials = Testimonial::select(["name", "id", "client_name_one", "image_one", "rating_one", "description_one", "client_name_two", "image_two", "rating_two", "description_two", "client_name_three", "image_three", "rating_three", "description_three"])->where('status', '=', 0)->where('id', $estimate->testimonial_id)->where('company_id', $company_id)->get()->first();
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf::setHeaderCallback(function ($pdf) {
            if ($pdf->PageNo() > 1) {
                $image_file = public_path('storage/logo.png');
                $pdf->Image($image_file, 164, 2, 40, '', '', '', 'T', false, 300, '', false, false, 0, false, false, false);
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
            }
        });

// Custom Footer
        $pdf::setFooterCallback(function ($pdf) use ($proposal_template) {

            $footer = '<table cellpadding="6"><tr style="background-color:' . $proposal_template->theme_color_two . ';"><td><a href="https://heavendesigns.in" target="_blank" style="text-decoration: none;color:' . $proposal_template->theme_color_one . ';">Heaven Designs</a></td><td style="text-align: right;color:' . $proposal_template->theme_color_one . ';">Social Media Link</td></tr></table>';
            $pdf->SetY(-9.6);
            $pdf->writeHTML($footer, true, false, true, false, '');
        });

        $pdf::SetAuthor('System');
        $pdf::SetTitle('My Report');
        $pdf::SetSubject('Report of System');


        //First page
        $pdf::SetMargins(0, 0, 0, false);
        $pdf::SetFontSubsetting(false);
        $pdf::SetFontSize('12px');
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::SetAutoPageBreak(false, PDF_MARGIN_FOOTER);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf.cover-page', compact('estimate','proposal_template', 'company_data'));
        $html = $view->render();

        $pdf::writeHTML($html, true, false, true, false, '');

        //Second page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, 15.5, 7, false);
        $pdf::SetFontSubsetting(true);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf.about-page', compact('estimate','proposal_template', 'company_data'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Third page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, 15.5, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf.product-page', compact('products', 'proposal_template', 'company_data'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Fourth page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, 15.5, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf.estimate-page', compact('estimate', 'estimate_items',  'proposal_template', 'company_data'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Fifth page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, 15.5, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 10);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf.term-and-condition-page', compact('estimate', 'proposal_template'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Sixth page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, 15.5, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf.testimonial-page', compact('proposal_template', 'company_data', 'testimonials'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        //Seven page
        $pdf::startPageGroup();
        $pdf::SetMargins(7, 15.5, 7, false);
        $pdf::SetAutoPageBreak(true, PDF_MARGIN_FOOTER);
        $pdf::SetFont('helvetica', 'R', 11);
        $pdf::AddPage('P', 'A4');

        $view = \View::make('pdf.thank-you-page', compact('company_data'));
        $html = $view->render();
        $pdf::writeHTML($html, true, false, true, false, '');

        $pdf::Output('hello_world.pdf', 'I');
//        return $pdf::Output(public_path('storage/document/hello_world.pdf'),'F');

//        PDF::lastPage();
    }
}
