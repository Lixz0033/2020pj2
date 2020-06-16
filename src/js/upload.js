//图片预览功能及上传的部分功能
function readAsDataURL() {
    let file = document.getElementById("file").files[0]; //检验是否为图像文件
    if (!/image\/\w+/.test(file.type)) {
        document.getElementById('img-message').innerText = "请选择图片文件类型";
        return false;
    } else if(file.size > 1024000){
        document.getElementById('img-message').innerText = "请选择大小小于1000kb的图片文件";
        return false;
    }
    else {
        document.getElementById('img-message').innerText = "";
        let reader = new FileReader();
        reader.readAsDataURL(file);//将文件以Data URL形式读入页面
        reader.onload = function (e) {
            let showImg = document.getElementById("show");
            showImg.src = this.result;//预览
        }
    }
}

//二级联动

function setSelectCity() {
    $("#slt-cty").empty();
    let cty = document.getElementById("slt-cty");
    let ctr = document.getElementById("slt-ctr");
    cty.value = 0;
    let ctrValue = ctr.value;

    if(city[ctrValue].length > 4000)
        cty.length = 4000;
    else
        cty.length = city[ctrValue].length + 1;
    for (let i = 1; i < cty.length; i++) {
        cty[i].innerText = city[ctrValue][i - 1];
        cty[i].value = i;
    }
}