<!DOCTYPE html>
<html lang="en">
  <head>
      <!-- Meta, title, CSS, favicons, etc. -->
          <meta charset="utf-8">
          <meta http-equiv="X-UA-Compatible" content="IE=edge">
          <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">

<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.2.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>
<body>
<div class="row">
        <form method="post" action="{spurl r='protected/main/index'}">
  <div class="col-lg-6">
      <div class="input-group">
                            <input type="text" name="keyword" val="{$keyword}" class="form-control">
                     <span class="input-group-btn">

                    <input class="btn btn-default" type="submit" value="搜索!" />
               </span>
                                    </div><!-- /input-group -->
                                      </div><!-- /.col-lg-6 -->
         </form>
   </div><!-- /.row -->
<div class="row">
{foreach $data as $val}
<div class="col-sm-6 col-md-4">
<div class="thumbnail">
    <img style="width:300px; height:300px" data-src="holder.js/300x300" src="{$val.image}" alt="...">
<div class="caption">
<h3>{$val.title}</h3>
<p>{$val.content|strip_tags}</p>
<p><a href="{spurl r='protected/main/detail' id=$val.id}" class="btn btn-primary" role="button">查看详情</a></p>
</div>
</div>
</div>
{/foreach}
</div>
<div class="row">
<ul class="pagination">
        {$html}
</ul>
</div>
</body>
</html>
