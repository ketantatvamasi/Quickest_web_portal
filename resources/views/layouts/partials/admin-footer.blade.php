</div>
@auth
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    {{date('Y')}} © {{ config('app.name', 'Laravel') }} - Coderthemes.com
                </div>
                <div class="col-md-6">
                    <div class="text-md-end footer-links d-none d-md-block">
                        <a href="javascript: void(0);">About</a>
                        <a href="javascript: void(0);">Support</a>
                        <a href="javascript: void(0);">Contact Us</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@else
    <footer class="footer footer-alt">
        {{date('Y')}} © {{ config('app.name', 'Laravel') }} - Coderthemes.com
    </footer>
@endauth

