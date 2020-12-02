@if (Auth::check())
<?php 
$listOptions = [
    'json' => Auth::user()->notifications()->select('id','type','data','created_at','read_at')->get()->toArray(),
    'header' => 'Notifications',
    'header_html_tag' => 'h2',
    'header_class' => 'purple',
    'css' => ['padding'=>'1em 0'],
    'li_class' => 'flexbox spread',
    'li_css' => ['padding'=>'0.2em 0.6em','position'=>'relative'],
    'ul_css' => ['maxHeight'=>'calc(100vh - 25em)','overflow'=>'visible hidden'],
    'limit' => 'none'
];
session( [ 'notification_ids' => collect($listOptions['json'])->map(function($n){return $n['id'];})->toArray() ] );
?>
<div id="Notifications" class='tab' data-afterdropdown='Notification.check_height' data-afterdropdownhide='Notification.check_height'>
    <div class="title" data-image="/images/icons/mail_icon_yellow.png">
        Notifications
    </div>
    <div class="underline" style="width: 0%;"></div>
    <div class="dropDown">
        <div class="List" data-options='{{ json_encode($listOptions) }}'></div>
        <div class="options">
            <div class='pinkBg10 multiBtns'>
                <div class="button pink xsmall" data-action='Notification.mark_as_unread'>mark unread</div>
                <div class="button pink xsmall" data-action='Notification.mark_as_read'>mark read</div>
                <div class="button pink xsmall" data-action='Notification.delete'>delete</div>
            </div>
            <div class="yellowBg10 multiBtns">
                <div class="button yellow xsmall" data-action='Notification.select_all'>select all</div>
                <div class="button yellow xsmall" data-action='Notification.unselect_all'>unselect all</div>
            </div>
        </div>                
    </div>
</div>
@endif