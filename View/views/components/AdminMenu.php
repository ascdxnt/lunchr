<aside class="admin-menu sidebar-shadow min-vh-100 overflow-hidden general-shadow">
	<img src="./View/assets/images/covao-logo.png" class="logo-covao mt-4" alt="Logo COVAO">
	<nav class="menu">
		<ul class="menu-list">
			<li class="menu-list__item">
				<a href="./?dir=admin&controller=AdminStatistics&action=Index" class="menu-list__link">
					<i class="fa-solid fa-signal me-1"></i>
					Estadísticas
				</a>
			</li>
			<li class="menu-list__item accordion-item">
				<a class="menu-list__link accordion-toggle" href="#" data-target="submenu-asistencia">
					<i class="fa-solid fa-star me-1"></i>
					Asistencia
					<i class="fa-solid fa-chevron-down accordion-chevron ms-auto"></i>
				</a>
				<ul class="accordion-submenu" id="submenu-asistencia">
					<li><a class="submenu-link" href="./?dir=admin&controller=Attendance&action=Index"><i class="fa-solid fa-clipboard-check me-1"></i>Pasar Asistencia</a></li>
					<li><a class="submenu-link" href="./?dir=admin&controller=Attendance&action=AttendanceRecord"><i class="fa-solid fa-list me-1"></i>Ver Registro</a></li>
				</ul>
			</li>
			<li class="menu-list__item">
				<a href="./?dir=admin&controller=Sections&action=Index&id=main" class="menu-list__link">
					<i class="fa-solid fa-people-roof me-1"></i>
					Secciones
				</a>
			</li>
			<li class="menu-list__item">
				<a href="./?dir=admin&controller=Specialties&action=Index&id=main" class="menu-list__link">
					<i class="fa-solid fa-microchip me-1"></i>
					Especialidades
				</a>
			</li>
			<li class="menu-list__item accordion-item">
				<a class="menu-list__link accordion-toggle" href="#" data-target="submenu-usuarios">
					<i class="fa-solid fa-user-gear me-1"></i>
					Usuarios
					<i class="fa-solid fa-chevron-down accordion-chevron ms-auto"></i>
				</a>
				<ul class="accordion-submenu" id="submenu-usuarios">
					<li><a class="submenu-link" href="./?dir=admin&controller=Student&action=Index&id=main"><i class="fa-solid fa-user-graduate me-1"></i>Estudiantes</a></li>
					<li><a class="submenu-link" href="./?dir=admin&controller=Teacher&action=Index&id=main"><i class="fa-solid fa-chalkboard-user me-1"></i>Profesores</a></li>
					<li><a class="submenu-link" href="./?dir=admin&controller=Staff&action=AdminViews&id=main"><i class="fa-solid fa-user-shield me-1"></i>Administradores</a></li>
					<li><a class="submenu-link" href="./?dir=admin&controller=Staff&action=BillingViews&id=main"><i class="fa-solid fa-cash-register me-1"></i>Cobros</a></li>
				</ul>
			</li>
			<li class="menu-list__item">
				<a href="./?dir=admin&controller=Settings&action=Index" class="menu-list__link">
					<i class="fa-solid fa-gear me-1"></i>
					Ajustes
				</a>
			</li>
		</ul>
	</nav>
</aside>

<style>
	.accordion-item {
		padding-bottom: 0 !important;
	}
	.accordion-toggle {
		display: flex;
		align-items: center;
		width: 100%;
		padding: 0.5em 0;
	}
	.accordion-chevron {
		font-size: 0.75rem;
		transition: transform 250ms ease;
		margin-left: auto;
	}
	.accordion-item.open .accordion-chevron {
		transform: rotate(180deg);
	}
	.accordion-submenu {
		list-style: none;
		padding: 0.25em 0 0.5em 0.5em;
		margin: 0;
		overflow: hidden;
		max-height: 0;
		transition: max-height 300ms ease, opacity 250ms ease;
		opacity: 0;
	}
	.accordion-item.open .accordion-submenu {
		max-height: 300px;
		opacity: 1;
	}
	.submenu-link {
		display: block;
		padding: 0.4em 0.75em;
		color: var(--text-color);
		text-decoration: none;
		font-size: 0.95rem;
		border-radius: 0.35em;
		transition: background-color 150ms, color 150ms;
	}
	.submenu-link:hover {
		background-color: var(--main-color);
		color: white !important;
	}
	.accordion-item:hover {
		background-color: transparent !important;
		transform: none !important;
	}
	.accordion-item:hover > a {
		color: var(--text-color) !important;
	}
	.accordion-item.open > a,
	.accordion-item.open > a i {
		color: var(--main-color) !important;
	}
</style>

<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
	<button type="button" class="position-absolute btn-close text-reset" style="right: 10px; top: 10px" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	<div class="offcanvas-body">
		<img src="./View/assets/images/covao-logo.png" class="logo-covao mt-0" alt="Logo COVAO">
		<nav class="menu">
			<ul class="menu-list">
				<li class="menu-list__item">
					<a href="./?dir=admin&controller=AdminStatistics&action=Index" class="menu-list__link">
						<i class="fa-solid fa-signal me-1"></i>
						Estadísticas
					</a>
				</li>
				<li class="menu-list__item accordion-item">
					<a class="menu-list__link accordion-toggle" href="#" data-target="submenu-asistencia-mobile">
						<i class="fa-solid fa-star me-1"></i>
						Asistencia
						<i class="fa-solid fa-chevron-down accordion-chevron ms-auto"></i>
					</a>
					<ul class="accordion-submenu" id="submenu-asistencia-mobile">
						<li><a class="submenu-link" href="./?dir=admin&controller=Attendance&action=Index"><i class="fa-solid fa-clipboard-check me-1"></i>Pasar Asistencia</a></li>
						<li><a class="submenu-link" href="./?dir=admin&controller=Attendance&action=AttendanceRecord"><i class="fa-solid fa-list me-1"></i>Ver Registro</a></li>
					</ul>
				</li>
				<li class="menu-list__item">
					<a href="./?dir=admin&controller=Sections&action=Index&id=main" class="menu-list__link">
						<i class="fa-solid fa-people-roof me-1"></i>
						Secciones
					</a>
				</li>
				<li class="menu-list__item">
					<a href="./?dir=admin&controller=Specialties&action=Index&id=main" class="menu-list__link">
						<i class="fa-solid fa-microchip me-1"></i>
						Especialidades
					</a>
				</li>
				<li class="menu-list__item accordion-item">
					<a class="menu-list__link accordion-toggle" href="#" data-target="submenu-usuarios-mobile">
						<i class="fa-solid fa-user-gear me-1"></i>
						Usuarios
						<i class="fa-solid fa-chevron-down accordion-chevron ms-auto"></i>
					</a>
					<ul class="accordion-submenu" id="submenu-usuarios-mobile">
						<li><a class="submenu-link" href="./?dir=admin&controller=Student&action=Index&id=main"><i class="fa-solid fa-user-graduate me-1"></i>Estudiantes</a></li>
						<li><a class="submenu-link" href="./?dir=admin&controller=Teacher&action=Index&id=main"><i class="fa-solid fa-chalkboard-user me-1"></i>Profesores</a></li>
						<li><a class="submenu-link" href="./?dir=admin&controller=Staff&action=AdminViews&id=main"><i class="fa-solid fa-user-shield me-1"></i>Administradores</a></li>
						<li><a class="submenu-link" href="./?dir=admin&controller=Staff&action=BillingViews&id=main"><i class="fa-solid fa-cash-register me-1"></i>Cobros</a></li>
					</ul>
				</li>
				<li class="menu-list__item">
					<a href="./?dir=admin&controller=Settings&action=Index" class="menu-list__link">
						<i class="fa-solid fa-gear me-1"></i>
						Ajustes
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>

<script>
	document.querySelectorAll('.accordion-toggle').forEach(toggle => {
		toggle.addEventListener('click', e => {
			e.preventDefault();
			const item = toggle.closest('.accordion-item');
			const isOpen = item.classList.contains('open');
			document.querySelectorAll('.accordion-item.open').forEach(i => i.classList.remove('open'));
			if (!isOpen) item.classList.add('open');
		});
	});

	// Auto-open if current URL matches a submenu link
	document.querySelectorAll('.accordion-submenu .submenu-link').forEach(link => {
		if (window.location.href.includes(link.getAttribute('href').replace('./', ''))) {
			link.closest('.accordion-item').classList.add('open');
		}
	});
</script>
