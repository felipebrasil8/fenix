
        <!-- ====================== -->
        <!--  footer.html           -->
        <!-- ====================== -->

        <!-- Rodapé do sistema | .main-footer -->
        <footer class="main-footer" id="main-footer">

            <!-- JS angular - módulos do sistema -->
            <script src="{{ asset ('/js/modulos/configuracao.js') }}"></script>
            <script src="{{ asset ('/js/modulos/home.js') }}"></script>
            <script src="{{ asset ('/js/modulos/rh.js') }}"></script>
            <script src="{{ asset ('/js/modulos/logs.js') }}"></script>
            <script src="{{ asset ('/js/modulos/ticket.js') }}"></script>
            <script src="{{ asset ('/js/jquery.slimscroll.min.js') }}"></script>

            @stack('scripts')  
            
            <!-- Informação da esquerda -->
            <strong>Copyright &copy; {{$copyright_data}} <a href="http://www.novaxtelecom.com.br" target="_blank">Novax Telecom</a>.</strong>

            <!-- Informação da direira -->
            <div class="pull-right hidden-xs">
                {{$versaoSistema->valor_texto}}
            </div>

        </footer>
        <!-- /.main-footer -->

        <!-- ====================== -->
        <!--  /footer.html          -->
        <!-- ====================== -->