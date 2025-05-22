$(document).ready(function (){

    const inputSearch = $(".interactive_search");
    let searchTimeout;

    inputSearch.on('input', function() {
        clearTimeout(searchTimeout);
        let val = $(this).val();

        searchTimeout = setTimeout(() => {
            if(val.length >= 3) {
                getSearchResult(val);
            } else {
                $(".result-interactive-search").hide().html("");
            }
        }, 700);
    });
});


function getSearchResult(val,link=0){
    $.ajax({
        type:"get",
        url:"/search/search_html.php",
        data: {q:val},
        dataType:'html',
        success:function(data){
            if(data.length>1){
                $(".result-interactive-search").show();
                $(".result-interactive-search").html(data);
            }else{
            }
        },
        beforeSend:function (){
            //$(".result-interactive-search-res").html('<div class="cnt_loader"><div class="loader_mini" style="">Загружаю..</div></div>');
        },
        error:function(jqXHR, exception){
            console.log(jqXHR);
        },
    });
}