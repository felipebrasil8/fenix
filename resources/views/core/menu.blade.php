
<!-- ====================== -->
<!--  menu.html         -->
<!-- ====================== -->

@if( !empty( auth()->user() ) )

<!-- Menu lateral esquerdo | .main-sidebar -->
<aside class="main-sidebar" id="main-sidebar" >
    
    <section class="sidebar" id="app_menu">
        <vc-menu menus="{{$new_menu_usuario}}"></vc-menu>
    </section>
    
</aside>
<!-- /.main-sidebar -->

@push('scripts')
    <!-- VUE.JS -->
    <script src="{{ asset ('/js/menu.js') }}"></script>
@endpush

@endif

<!-- ====================== -->
<!--  /menu.html            -->
<!-- ====================== --> 