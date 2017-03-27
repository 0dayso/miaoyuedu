<script type="text/javascript">
//菜单激活状态
    Do.ready('core', function () {
        if (hsConfig.CONTROLLER === 'User') {
            switch (hsConfig.ACTION) {
                case 'personal':                    
                    $('#nav_personal').addClass('active');
                    break;
                case 'shelf':
                    $('#nav_shelf').addClass('active');
                    break;
                case 'paylogs':
                    $('#nav_paylogs').addClass('active');
                    break;
                case 'salelogs':
                    $('#nav_salelogs').addClass('active');
                    break;
                case 'setbooking':
                    $('#nav_setbooking').addClass('active');
                    break;
                case 'shuquan':
                    $('#nav_shuquan').addClass('active');
                    break;
                case 'authorreg':
                    $('#nav_author').addClass('active');
                    break;
            }
        } else if (hsConfig.CONTROLLER === 'Pay') {
            $('#nav_pay').addClass('active');
        } else if (hsConfig.CONTROLLER === 'Index') {
            $('#nav_index').addClass('active');
        } else if (hsConfig.CONTROLLER === 'Channel') {
            if(hsConfig.ACTION == 'search'){
            $('#nav_search').addClass('active');}
        }
    });
</script>