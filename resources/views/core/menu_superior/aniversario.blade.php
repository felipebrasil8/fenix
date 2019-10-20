<!-- Aniversarios Menu -->
<li class="dropdown messages-menu tooltip-delay" data-toggle="tooltip" data-placement="left" title="" data-original-title="Aniversários">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-birthday-cake"></i>
        <span class="label bg-orange" ng-show="funcionarios_niver.length > 0">@{{funcionarios_niver.length}}</span>
    </a>
    <ul class="dropdown-menu">
        <li>
            <!-- Inner Menu: contains the notifications -->
            <ul class="menu">
                
                <li ng-repeat="funcionario in funcionarios_niver" ng-show="funcionarios_niver.length > 0">
                   <div>
                       <div class="pull-left">
                           <img ng-src="/rh/funcionario/avatar-grande/@{{funcionario.id}}" alt="@{{funcionario.nome}}">
                       </div>
                       <h4>
                            <a href="/rh/funcionario/@{{funcionario.id}}">
                                @{{funcionario.nome | ucfirst}}
                            </a>
                            <a href="mailto:@{{funcionario.email}}" class="pull-right">
                               <i class="fa fa-envelope"></i>
                            </a>
                            <a href="#" class="pull-right" style="padding-right: 10px;" data-toggle="tooltip" data-placement="left" title="*@{{funcionario.ramal}}">
                               <i class="fa fa-phone-square"></i>
                            </a>                            
                       </h4>
                       <p>@{{funcionario.dt_nascimento}}</p>
                   </div>
               </li>
               <li ng-show="funcionarios_niver.length == 0">
                    <div>
                       <h4>                            
                            Nenhum aniversário hoje.
                       </h4>
                   </div>
               </li>
            </ul>
        </li>
        <li class="footer"><a ng-click="modalTodosAniversarios()" data-toggle="modal" class="cursor-pointer">Visualizar todos os aniversários</a></li>
    </ul>
</li>

<script type="text/ng-template" id="todosAniversariosModal.html">
    <div class="modal-header">
        <button type="button" class="close" ng-click="modalCancelarTodosAniversarios()" aria-label="Fechar">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="modal-title"><i class="fa fa-birthday-cake text-yellow"></i> Próximos aniversários</h4>
    </div>
    <div class="modal-body modal-body-todos-aniversarios">
      <div class="usuario-todos-aniversarios" ng-repeat="funcionario in funcionarios">
          <img ng-src="/rh/funcionario/avatar-grande/@{{funcionario.id}}" alt="@{{funcionario.nome}}" style="@{{funcionario.imagem}}">
          <a class="users-list-name" href="/rh/funcionario/@{{funcionario.id}}">@{{funcionario.nome | ucfirst}}</a>
          <span class="users-list-date @{{funcionario.text_class}}">@{{funcionario.dt_nascimento}}</span>
      </div> 
    </div>
</script>

