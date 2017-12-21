<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Pre MWIT 2018 | Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="/node_modules/materialize-css/dist/css/materialize.min.css" rel="stylesheet">
	<link href="/node_modules/tether/dist/css/tether.min.css" rel="stylesheet">
	<script src="/node_modules/jquery/dist/jquery.js"></script>
	<script src="/node_modules/tether/dist/js/tether.min.js"></script>
	<script src="/node_modules/materialize-css/dist/js/materialize.min.js"></script>
	<link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet">
	<style>
		canvas {
			border: 1px solid #d3d3d3;
			background-color: #42ff64;
		}

		button {
			margin: 5px;
		}
	</style>
	<script>
		var wingoal = 500000;
		var dmgdiff = 0;

		var bossex = 1;
		var bossamp = 1;
		var stdex = 1;
		var stdamp = 1;
		var stdadd = 0;
		var time = 0;
		var boss_tempmod = 1;
		var stud_tempmod = 1;
		var booster_countdown = 0;
		var stun = 1;
		var bossphase = 1;
		var tincrement = 1;


		var item_13_check = 0;
		var item_14_check = 0;

		var healthbar = {
			canvas: document.createElement("canvas"),
			start: function () {
				this.canvas.width = 480;
				this.canvas.height = 50;
				this.context = this.canvas.getContext("2d");
				document.body.insertBefore(this.canvas, document.body.childNodes[0]);
				bosshp = new component(480, 50, "red", -240, 0);
			}

		}

		healthbar.start();

		function component(width, height, color, x, y, type) {
			this.type = type;
			this.score = 0;
			this.width = 480;
			this.height = 50;
			this.speedX = 0;
			this.speedY = 0;
			this.x = x;
			this.y = y;
			this.update = function () {
				ctx = healthbar.context;
				if (this.type == "text") {
					ctx.font = this.width + " " + this.height;
					ctx.fillStyle = color;
					ctx.fillText(this.text, this.x, this.y);
				} else {
					this.x = gethpx();
					ctx.fillStyle = "#42ff64";
					ctx.fillRect(0, 0, 480, 50);
					ctx.fillStyle = color;
					ctx.fillRect(0, 0, this.x, this.height);
					console.log(this.width);
				}

			}
		}

		bosshp.update();

		function bossdmg(damage) {
			dmgdiff -= damage;
			bosshp.update();
			console.log(bosshp.x);
		}

		function studentdmg(damage) {
			dmgdiff += damage;
			bosshp.update();
			console.log(bosshp.x);
		}

		function gethpx() {
			var x = 480 * (dmgdiff + wingoal) / (wingoal * 2);
			return x;
		}




		function getdpss() {
			return (100 + stdex) * (1 + 0.5 * (1.2 * Math.log(Math.E + (time / 50)))) * (1.2 * Math.log(Math.E + (time / 50))) *
				(stdamp) + (stdadd);
		}

		function getdpsb1() {
			return (150 + bossex) * (1 + 0.5 * (1 * Math.log(Math.E + (time / 50))) * (1 * Math.log(Math.E + (time / 50))) *
				Math.log(400 / getbossperc())) * (bossamp);
		}

		function getdpsb2() {
			return (275 + bossex) * (1 + 0.5 * (1.2 * Math.log(Math.E + (time / 100))) * (1.2 * Math.log(Math.E + (time / 100))) *
				Math.log(400 / getbossperc())) * (bossamp);
		}


		function damagetoboss() {
			dmgdiff -= getdpss() * 0.1;
		}

		function damagetostd() {
			if (bossphase == 1)
				dmgdiff += (getdpsb1() / 10);
			else
				dmgdiff += (getdpsb2() / 10);
		}


		function timer() {
			time += tincrement;
		}

		function stun_boss(seconds) {
			stun = seconds;
			stundur = setInterval(function () {
				stun_countdown();
			}, 1000);

		}

		function stun_countdown() {
			stun -= 1;
			if (stun == 0) {
				clearInterval(stundur);
			}
		}

		function getbossperc() {
			return (500000 + dmgdiff) / 10000;
		}
		setInterval(function () {
			console.log(dmgdiff)
		}, 100);
		setInterval(function () {
			console.log("Boss dps =" + getdpsb1())
		}, 100);
		setInterval(function () {
			console.log("Student dps =" + getdpss())
		}, 100);
		setInterval(function () {
			timer();
		}, 1000);
		setInterval(function () {
			damagetoboss()
		}, 100);
		setInterval(function () {
			damagetostd()
		}, 100);
		setInterval(function () {
			bosshp.update()
		}, 100);


		setInterval(function () {
			timer();
		}, 1000);

		function manual_atk(dmg) {
			dmgdiff -= dmg;
			if (item_13_check == 1) {
				stdex += 0.5;
			}
			if (item_14_check == 1) {
				stdadd += 0.1;
			}
		}

		function item_main_1() {
			stun_boss(5);
		}

		function item_main_2() {
			stdadd += 10;
		}

		function item_main_3() {
			stdex += 20;
		}

		function item_main_4() {
			dmg = getdpss() * 5;
			dmgdiff = dmgdiff - dmg;
		}

		function item_extra_1() {
			bossamp = bossamp * 0.95;
		}

		function item_extra_2() {
			stdamp = stdamp * 1.05;
		}

		function item_extra_3() {
			time = time / 1.25;
			tincrement = tincrement / 1.25;
		}

		function item_extra_4() {
			stdadd += 50;
		}

		function item_extra_6() {
			stdex += 100;
		}

		function item_extra_7() {
			if (bossphase == 1) {
				dmg = getdpsb1() * 0.75;
			} else {
				dmg = getdpsb2() * 0.75;
			}
			dmgdiff = dmgdiff - dmg;
		}

		function bstcountdown_std() {
			booster_countdown -= 1;
			if (booster_countdown == 0) {
				clearInterval(a);
				stud_tempmod /= 2;
			}
		}

		function item_extra_8() {
			stud_tempmod *= 2;
			booster_countdown = 60;
			a = setInterval(function () {
				bstcountdown_std()
			}, 1000);
		}

		function bstcountdown_boss() {
			booster_countdown -= 1;
			if (booster_countdown == 0) {
				clearInterval(a);
				boss_tempmod *= 2;
			}
		}

		function item_extra_9() {
			boss_tempmod /= 2;
			booster_countdown = 60;
			a = setInterval(function () {
				bstcountdown_boss()
			}, 1000);
		}

		function item_extra_10() {
			stun_boss(15);
		}

		function item_extra_11() {
			time -= 100;
		}

		function item_extra_12() {
			dmgdiff = ((dmgdiff + 500000) / 2) - 500000;
		}

		function item_extra_13() {
			item_13_check = 1;
		}

		function item_extra_14() {
			item_14_check = 1;
		}
	</script>
</head>

<body>

	</script>
	<ul class="collapsible popout" data-collapsible="accordion">
		<li>
			<div class="collapsible-header">
				Manual Attack
			</div>
			<div class="collapsible-body">
				<button class="btn waves-effect waves-light" onclick="manual_atk(500)">MNL ATK 500</button>
				<button class="btn waves-effect waves-light" onclick="manual_atk(10000)">MNL ATK 10000</button>


			</div>
		</li>
		<li>
			<div class="collapsible-header">
				Common Items
			</div>
			<div class="collapsible-body">
				<button class="btn waves-effect waves-light" onclick="item_main_1()">STUN BOSS 5</button>
				<button class="btn waves-effect waves-light" onclick="item_main_2()">DPS B +10</button>
				<button class="btn waves-effect waves-light" onclick="item_main_3()">ATK B +20</button>
				<button class="btn waves-effect waves-light" onclick="item_main_4()">INST DAMAGE 5 SEC</button>
			</div>
		</li>
		<li>
			<div class="collapsible-header">
				Rare Items
			</div>
			<div class="collapsible-body">
				<button class="btn waves-effect waves-light" onclick="item_extra_1()">BOSS DPS 0.95</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_2()">STUDENT DPS 1.05</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_3()">Time/1.25</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_5()">DPS B +50</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_6()">ATK B +100</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_7()">BOSS DMG COUNTER 0.75</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_8()">DMGx2 1 MIN</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_9()">BOSSx0.5 1MIN</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_10()">SNOWBALL STUN</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_11()">FLASHBACK</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_12()">%BOSS / 2</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_13()">MANUAL ATK ATK+0.5</button>
				<button class="btn waves-effect waves-light" onclick="item_extra_14()">MANUAL ATK DPS+0.1</button>
			</div>
		</li>
	</ul>
<script>
function executeQuery() {
    $.ajax({
        type: "POST",
        url: "node_modules/backend/pushapi.php",
        data: {
            hp: dmgdiff,
        },
        success: function (data) {
            console.log(data);
        }
    });
    setTimeout(executeQuery, 1000);
}
executeQuery();
    $(".btn").click(function(){
        console.log("clicked");
        console.log(dmgdiff);

    });
</script>
</html>