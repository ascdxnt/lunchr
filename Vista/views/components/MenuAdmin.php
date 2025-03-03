<aside class="admin-menu sidebar-shadow min-vh-100 overflow-hidden general-shadow">
	<img src="./Vista/assets/images/covao-logo.png" class="logo-covao mt-4" alt="Logo COVAO">
	<nav class="menu">
		<ul class="menu-list">
			<li class="menu-list__item">
				<a href="./?dir=admin&controlador=EstadisticasAdmin&accion=Index" class="menu-list__link">
					<i class="fa-solid fa-signal me-1"></i>
					Estadísticas
				</a>
			</li>
			<li class="dropdown menu-list__item">
				<a class="dropdown-toggle menu-list__link" href="#" role="button" id="" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="fa-solid fa-star me-1"></i>
					Asistencia
				</a>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
					<li><a class="dropdown-item" href="./?dir=admin&controlador=Asistencia&accion=Index">Pasar Asistencia</a></li>
					<li><a class="dropdown-item" href="./?dir=admin&controlador=Asistencia&accion=RegistroAsistencia">Ver Registro</a></li>
				</ul>
			</li>
			<li class="menu-list__item">
				<a href="./?dir=admin&controlador=Secciones&accion=Index&id=main" class="menu-list__link">
					<i class="fa-solid fa-people-roof me-1"></i>
					Secciones
				</a>
			</li>
			<li class="menu-list__item">
				<a href="./?dir=admin&controlador=Especialidades&accion=Index&id=main" class="menu-list__link">
					<i class="fa-solid fa-microchip me-1"></i>
					Especialidades
				</a>
			</li>
			<li class="dropdown menu-list__item">
				<a class="dropdown-toggle menu-list__link" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
					<i class="fa-solid fa-user-gear me-1"></i>
					Usuarios
				</a>
				<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
					<li><a class="dropdown-item" href="./?dir=admin&controlador=Estudiante&accion=Index&id=main">Estudiantes</a></li>
					<li><a class="dropdown-item" href="./?dir=admin&controlador=Profesor&accion=Index&id=main">Profesores</a></li>
					<li><a class="dropdown-item" href="./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main">Administradores</a></li>
					<li><a class="dropdown-item" href="./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main">Cobros</a></li>
				</ul>
			</li>
			<li class="menu-list__item">
				<a href="./?dir=admin&controlador=Ajustes&accion=Index" class="menu-list__link">
					<i class="fa-solid fa-gear me-1"></i>
					Ajustes
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
					<a href="./?dir=admin&controlador=EstadisticasAdmin&accion=Index" class="menu-list__link">
						<i class="fa-solid fa-signal me-1"></i>
						Estadísticas
					</a>
				</li>
				<li class="dropdown menu-list__item">
					<a class="dropdown-toggle menu-list__link" href="#" role="button" id="" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fa-solid fa-star me-1"></i>
						Asistencia
					</a>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<li><a class="dropdown-item" href="./?dir=admin&controlador=Asistencia&accion=Index">Pasar Asistencia</a></li>
						<li><a class="dropdown-item" href="./?dir=admin&controlador=Asistencia&accion=RegistroAsistencia">Ver Registro</a></li>
					</ul>
				</li>
				<li class="menu-list__item">
					<a href="./?dir=admin&controlador=Secciones&accion=Index&id=main" class="menu-list__link">
						<i class="fa-solid fa-people-roof me-1"></i>
						Secciones
					</a>
				</li>
				<li class="menu-list__item">
					<a href="./?dir=admin&controlador=Especialidades&accion=Index&id=main" class="menu-list__link">
						<i class="fa-solid fa-microchip me-1"></i>
						Especialidades
					</a>
				</li>
				<li class="dropdown menu-list__item">
					<a class="dropdown-toggle menu-list__link" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fa-solid fa-user-gear me-1"></i>
						Usuarios
					</a>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
						<li><a class="dropdown-item" href="./?dir=admin&controlador=Estudiante&accion=Index&id=main">Estudiantes</a></li>
						<li><a class="dropdown-item" href="./?dir=admin&controlador=Profesor&accion=Index&id=main">Profesores</a></li>
						<li><a class="dropdown-item" href="./?dir=admin&controlador=Funcionario&accion=VistasAdmin&id=main">Administradores</a></li>
						<li><a class="dropdown-item" href="./?dir=admin&controlador=Funcionario&accion=VistasCobros&id=main">Cobros</a></li>
					</ul>
				</li>
				<li class="menu-list__item">
					<a href="./?dir=admin&controlador=Ajustes&accion=Index" class="menu-list__link">
						<i class="fa-solid fa-gear me-1"></i>
						Ajustes
					</a>
				</li>
			</ul>
		</nav>

	</div>
</div>
