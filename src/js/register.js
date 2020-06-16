//密码强度判别
$(document).ready(function(){
    $('#pwd').keyup(function(event){
        var password = $('#pwd').val();
        function getValue(password){
            var value = 0;
            if(password.length>=8){
                value = 1;
            }
            for(var i=0;i<password.length;i++){
                var current = password.charCodeAt(i);
                if(((current>=65&&current<=90)||(current>=97&&current<=122))&&password.length>=8){
                    value = 2;
                }
                if(((current>=33&&current<=47)||(current>=58&&current<=64)||(current>=91&&current<=96)||(current>=123&&current<=126))&&password.length>=8){
                    value = 3;
                    break;
                }
            }
            return value;
        }
        var value = getValue(password);
        if(value==0){
            $('#showStrength').text('低强度');
            $('#showStrength').css({'color':'red'});
        }else if(value==1){
            $('#showStrength').text('中低强度');
            $('#showStrength').css({'color':'orange'});
        }else if(value==2){
            $('#showStrength').text('中强度');
            $('#showStrength').css({'color':'blue'});
        }else if(value==3){
            $('#showStrength').text('高强度');
            $('#showStrength').css({'color':'green'});
        }
    });
});