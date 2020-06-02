<style>
 a:not([href]):not([tabindex]):hover {
    text-decoration: none;
    color: #fff;
}
.hide-menu .user-type {
    text-decoration: none;
    color: #fff;
}
.sidebar-dark .side-menu li a {
    color: #fff;
    font-size: 17px;
}
</style>



<?php
$profile_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM admins WHERE id='".$_SESSION['admin']."'"));
?>
<aside class="site-sidebar scrollbar-enabled clearfix ps ps--theme_default ps--active-y" data-ps-id="d59dfc42-2cc7-c0b1-cf68-87ad01c4613d">
<!-- User Details -->
<div class="side-user">
<a class="col-sm-12 media clearfix">
<div class="media-body hide-menu">
<h4 class="media-heading mr-b-5 text-uppercase"><?php echo $profile_data['name']; ?></h4><span class="user-type fs-12"><?php echo $profile_data['email']; ?></span>
</div>
</a>
<div class="clearfix"></div>
</div>
<!-- /.side-user -->
<!-- Sidebar Menu -->
<nav class="sidebar-nav">
    <ul class="nav in side-menu">
        <li class="menu-item-has-children">
			<a href="javascript:void(0);" class="ripple"><span class="color-color-scheme"><span class="hide-menu">Members</span></span></a>
			<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
				 <li><a href="merchant.php">Merchant</a></li>
				 <li><a href="user.php">Member</a></li>
			</ul>
        </li>
        <li><a href="merchants.php"><span class="hide-menu">Subscription</span></a></li>
        <li><a href="wallet.php"><span class="hide-menu">Wallet</span></a></li>
		<li><a href="transaction.php"><span class="hide-menu">Transaction</span></a></li>
		<li><a href="recharge.php"><span class="hide-menu">Recharge</span></a></li>
		<li><a href="request.php"><span class="hide-menu">Request</span></a></li>
		<li><a href="favorite.php"><span class="hide-menu">Favorite</span></a></li>
		<li class="menu-item-has-children">
			<a href="javascript:void(0);" class="ripple"><span class="color-color-scheme"><span class="hide-menu">Product Subscription</span></span></a>
			<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
				 <li><a href="add_sub.php">Add Subscription</a></li>
				 <li><a href="view_sub.php">View Subscription</a></li>
			</ul>
        </li>
        <!--<li class="menu-item-has-children">
			<a href="javascript:void(0);" class="ripple"><span class="color-color-scheme"><span class="hide-menu">Referral List</span></span></a>
			<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
				 <li><a href="referral_merchant.php">Merchant</a></li>
				 <li><a href="referral_member.php">Member</a></li>
			</ul>
        </li>-->
        <li><a href="referral_merchant.php"><span class="hide-menu">Referral List</span></a></li>
        <li><a href="community_merchant.php"><span class="hide-menu">Community Fund</span></a></li>
        <!--<li class="menu-item-has-children">
			<a href="javascript:void(0);" class="ripple"><span class="color-color-scheme"><span class="hide-menu">Community Fund</span></span></a>
			<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
				 <li><a href="community_merchant.php">Merchant</a></li>
				 <li><a href="community_member.php">Member</a></li>
			</ul>
        </li>-->
        <li><a href="subscribe.php"><span class="hide-menu">Subscribe</span></a></li>
        <li><a href="kType.php"><span class="hide-menu">K Type</span></a></li>
        <li><a href="total_commission.php"><span class="hide-menu">Commission</span></a></li>
		<li><a href="exchange_requests.php"><span class="hide-menu">Exchange Request</span></a></li>
		<li><a href="charges.php"><span class="hide-menu">Withdraw Charges</span></a></li>
		<li><a href="margin.php"><span class="hide-menu">Exchange Margin</span></a></li>
		<li><a href="contact.php"><span class="hide-menu">Contact</span></a></li>
		<li class="menu-item-has-children">
			<a href="javascript:void(0);" class="ripple"><span class="color-color-scheme"><span class="hide-menu">Work Diary</span></span></a>
			<ul class="list-unstyled sub-menu collapse" aria-expanded="true">
				 <li><a href="work_diary.php?type=1">Daily</a></li>
				 <li><a href="work_diary.php?type=2">Weekly</a></li>
				 <li><a href="work_diary.php?type=3">Monthly</a></li>
			</ul>
        </li>
        <li><a href="logout.php"><span class="hide-menu">Log Out</span></a></li>    
    </ul>
    <!-- /.side-menu -->
</nav>
<!-- /.sidebar-nav -->
<div class="ps__scrollbar-x-rail" style="left: 0px; bottom: 0px;"><div class="ps__scrollbar-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__scrollbar-y-rail" style="top: 0px; height: 523px; right: 0px;"><div class="ps__scrollbar-y" tabindex="0" style="top: 0px; height: 271px;"></div></div></aside>
