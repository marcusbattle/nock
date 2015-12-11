<p class="text-center">Select a network</p>
<ul class="data-list">
	<li class="network cell" ng-repeat="network in networks">
		<a href="#" ng-click="setNetwork($event)" data-network-id="{{network.userblog_id}}">
			<span>{{network.blogname}}</span>
		</a>
	</li>
</ul>