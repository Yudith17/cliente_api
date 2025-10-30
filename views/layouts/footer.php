</div>

<script>
    // Funciones comunes para todos los CRUDs
    function confirmDelete(message = '¿Estás seguro de que deseas eliminar este registro?') {
        return confirm(message);
    }

    // Navegación activa
    const currentPage = window.location.href;
    document.querySelectorAll('.nav-btn').forEach(btn => {
        if (btn.href === currentPage || currentPage.includes(btn.getAttribute('href').split('?')[0])) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });

    // Efectos hover
    document.querySelectorAll('.btn, .action-btn, .nav-btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-1px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });
</script>
</body>
</html>