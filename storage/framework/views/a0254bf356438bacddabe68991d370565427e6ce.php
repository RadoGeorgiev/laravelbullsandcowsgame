<?php $__env->startSection('title', 'Game'); ?>

<?php $__env->startSection('content'); ?>

	<nav class="navbar navbar-dark bg-info">
		<div><img src="/svg/cow.svg" style="height: 25px;"></div>
		<button id="newgamebtn" class="btn btn-outline-warning" type="button" data-toggle="modal" data-target="#player-modal">New Game</button>
		<h5>Bulls And Cows Game</h5>
		<button class="btn btn-outline-warning rules" type="button" data-toggle="modal" data-target="#rules-modal">Rules</button>
		<div><img src="/svg/cow.svg" style="height: 25px; transform: scaleX(-1);"></div>
	</nav>
	<main class="container">
		<div id="playcard" class="card text-center" style="display: none">
			<div id='header_container'>
				<div class="card-header">
					Players name
				</div>
			</div>
			<div class="card-body">
				<h5 id="number" class="card-title">The hidden number is:</h5>
				<form class="input-group mb-3" onsubmit="event.preventDefault(); guessNumber(); rowAdd()" id="play-form">
					<input  id="guess" class="form-control" type="text" maxlength="4" pattern="^(?:([0-9])(?!.*\1)){4}$" required autocomplete="off">
					<button class="btn btn-success" type="submit">GO</button>
				</form>
				<div id="results" class="card-text"></div>
			</div>
			<div class="card-footer text-muted">
				<button id="giveup" class="btn btn-danger" type="button">Give up</button>
			</div>
		</div>
		<div class="card text-center">
			<h5 style="padding: 15px;">Top 10 Players</h5>
			<table class="table table-sm">
				<thead>
				<tr>
					<th scope="col">#</th>
					<th scope="col">Player</th>
					<th scope="col">Tries</th>
					<th scope="col">Score</th>
				</tr>
				</thead>
				<tbody id="top-tbody">
				</tbody>
			</table>
		</div>
	</main>



<!-- Rules Modal -->

<div class="modal fade" id="rules-modal" tabindex="-1" role="dialog" aria-labelledby="rules-modal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="rules-modal">Rules of the game:</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				Test your skill and discover the hidden number!<br><br>
				Bulls = correct number, correct position.<br>
				Cows = correct number, wrong position.<br><br>
				Start with 1000 points and each try will cost you 25 points<br>
				More guesses = less points to your score!<br><br>
				Also no double figures allowed!<br>
				Good luck and have fun!
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Player Modal -->

<div class="modal fade" id="player-modal" tabindex="-1" role="dialog" aria-labelledby="player-modal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="player-modal">Enter players name</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form class="form-inline">
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text" id="basic-addon1"><img src="/svg/bull.svg" style="height: 23px;transform: scaleX(-1);"></span>
						</div>
						<input id="playername" type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button id="playnewgame" type="button" class="btn btn-success" data-dismiss="modal">Play!</button>
			</div>
		</div>
	</div>
</div>

<script>

$(function () {
	var highscoreObj = JSON.parse(<?php echo json_encode($highscore); ?>);
	var number = null;
	var name = '';
	var tries = 0;
	var score = 1000;

	$.each(highscoreObj, function(key, value){
		if (key > 9) {
			return false;
		}
		$('#top-tbody').append(
			'<tr>' +
					'<td scope="col">'+ (key + 1) +'</td>' +
					'<td scope="col">'+ value['name'] +'</td>' +
					'<td scope="col">'+ value['tries'] +'</td>' +
					'<td scope="col">'+ value['score'] +'</td>' +
			'</tr>'
		)
	})

	$('#playcard').hide();

	$(document).ready(function(){
		$("#playnewgame").on('click', function(event){
			$.ajax({
				type: 'GET',
				url: '/newgame',
				datatype: 'json',
				success: function(data){
					number = data['num'];
					console.log(number);
				}, 
				error: function(){
					console.log('AJAX did not work');
				}
			});

			var name = $('#playername').val();
			$('.card-header').html('<div>' + name + '\'s' + ' game</div>');
			$('#playcard').show();
		});
	});

	$('#giveup').on('click', function() {
		// show number and end game + disable GO btn
	});

	function guessNumber() {
		// Bulls and cows
		console.log(number);
	}

	$('#giveup').on('click', function() {
		// post score to file
		// $.ajax({
		// 	url : "postscore",
		// 	type: "POST",
		// 	data : [{"name": name, "tries": tries, "score": score}]
		// 	success: function(res)
		// 	{
		// 		console.log(res);
		// 	},
		// 	error: function (jqXHR, textStatus, errorThrown)
		// 	{
		//  		console.log('AJAX did not work');
		// 	}
		// });
	});

	function rowAdd() {
		tries += 1;
		score -= 25;
		console.log(tries);
		console.log(score);
	}

});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rado-pcloud/pcloud/example/bac/resources/views/newhome.blade.php ENDPATH**/ ?>