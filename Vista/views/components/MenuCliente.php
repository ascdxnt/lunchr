<aside class="admin-menu sidebar-shadow min-vh-100 overflow-hidden general-shadow">
	<img src="./Vista/assets/images/covao-logo.png" class="logo-covao mt-4" alt="Logo COVAO">
	<nav class="menu">
		<ul class="menu-list">
			<li class="menu-list__item">
				<a onclick="IrAInicio()" class="menu-list__link">
					<i class="fa-solid fa-house-chimney-user me-1"></i>
					Inicio
				</a>
			</li>
			<li class="menu-list__item">
				<a href="./?dir=cliente&controlador=ClienteEstadisticas&accion=Index" class="menu-list__link">
					<i class="fa-solid fa-signal me-1"></i>
					Estadísticas
				</a>
			</li>
		</ul>
	</nav>
</aside>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
	<button type="button" class="position-absolute btn-close text-reset" style="right: 10px; top: 10px" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	<div class="offcanvas-body">
		<img src="./Vista/assets/images/covao-logo.png" class="logo-covao mt-0" alt="Logo COVAO">
		<nav class="menu">
			<ul class="menu-list">
				<li class="menu-list__item">
					<a onclick="IrAInicio()" class="menu-list__link">
						<i class="fa-solid fa-house-chimney-user me-1"></i>
						Inicio
					</a>
				</li>
				<li class="menu-list__item">
					<a href="./?dir=cliente&controlador=ClienteEstadisticas&accion=Index" class="menu-list__link">
						<i class="fa-solid fa-signal me-1"></i>
						Estadísticas
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>
