function toggleMenu() {
    // Toggle the sidebar menu
    const menu = document.querySelector('#sidebar-menu');
    const menuIcon = document.querySelector('.bx-menu-alt-right')
    if (menu.style.left == '-200px') {
        menu.style.left = '0';
        menu.style.transition = 'left .3s ease';
        menuIcon.style.color = '#fff';
    } else {
        menu.style.left = '-200px';
        menu.style.transition = 'left .3s ease';
        menuIcon.style.color = '#CF2B2B';
    }
}

function logout() {
    $.ajax({
        type: "POST",
        url: "../ajax/logout.php",
        data: {
        },
        success: function (response) {
            if (JSON.parse(response) === true) window.location.href = "login.php";
        }
    });
}

// Despliegue de submódulos
$('.es-modulo').click(function () {
    if (!$(this).hasClass('desplegado')) {
        if ($(this).hasClass('es-padre')) {
            childClass = '.padre-' + $(this).attr('id')
            fatherMargin = parseInt($(this).css('margin-left').slice(0, -2))
            childMargin = (fatherMargin + 30) + 'px';
            $(childClass).css('margin-left', childMargin).removeClass('collapse')
            $(this).addClass('desplegado')
        }
    } else {
        $(childClass).addClass('collapse').css('margin-left', 0)
        $(this).removeClass('desplegado')
    }
})


