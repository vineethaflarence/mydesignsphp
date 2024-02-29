<!DOCTYPE>
<html>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <link rel="stylesheet" href="./bootmin/bootstrap.min.css">
   <link rel="stylesheet" href="./bootmin/font-awesome/css/font-awesome.min.css">
   <link rel="Stylesheet" type="text/css" href="style/gallery-styles.css">
   <link href="./style/simplePagination.css" type="text/css" rel="stylesheet"/>
    <link rel="stylesheet" href="./bootmin/font-awesome/css/font-awesome.min.css">
        <style type="text/css">
      .col-md-2 {
        padding-right: 5px;
        padding-left: 5px;
        padding-top: 1px;
      }
        </style>
  <script src="./scripts/jquery1.11.2.min.js"></script>
  <script src="./scripts/simplePagination.js"></script>
   <title>Student Achievements 2023</title>
</head>
<body class="gallery-achievements">
<div class="text-co">
<div class="container">
  <h1 class="ac-title">Student Achievements 2023</h1>
</div>
</div>
<div class="container">
    <div class="row show-dta" id="ft-con">
    </div>
    <div id="pagination"></div>
    </div>
  <script>  
    $(document).ready(function(){ 
		$.getJSON('.info-content .description', function (data) {
			var co_len = data.items.length;
			  if (co_len !== 0) {
				gettingdata(data);
				function gettingdata(data) {
				  data.items.map(function (item,index) {
						var index_v = index;
						var imgpath =  "<div class='zoom-view'><img src='"+item.src+"' class='bd-placeholder-img card-img-top' style='width: 100%;height: 225' ></div>";
						var content = item.content ;
						$(".show-dta").append("<div class='col-md-4 list-section' id='"+index_v+"'><div class='card mb-6 shadow-sm'>"+imgpath+" <div class='border-line'></div><div class='card-body' id='"+index_v+"'><p class='card-text'>"+item.content+"</p></div></div></div>"); 
						
					});
				}
				var items = $("#ft-con .list-section");
				var numItems = items.length;
				var perPage = 12;
				items.slice(perPage).hide();
				if(perPage < numItems){
				  $("#pagination").pagination({
				  items: numItems,
				  itemsOnPage: perPage,
				  cssStyle: "light-theme",
				  onPageClick: function(pageNumber) {
					var showFrom = perPage * (pageNumber - 1);
					var showTo = showFrom + perPage;
					items.hide()
					   .slice(showFrom, showTo).show();
				  }
				});
				}
			  }
			  $('.card-body').each(function() {
					var $pTag = $(this).find('p');
					if($pTag.text().length > 250){
					 var value_id = $(this).attr('id');
					 console.log(value_id);
						var shortText = $pTag.text();
						shortText = shortText.substring(0, 205);
						$pTag.addClass('fullArticle').hide();
						shortText +=  '<a class="a-read" href="student23-ind-profile.php?page_id='+ value_id +'"> ....</a>';
						$(this).append('<p class="card-text">'+shortText+'</p>');
					}

				});

			 
			});
      });
    </script>
</body>
</html>