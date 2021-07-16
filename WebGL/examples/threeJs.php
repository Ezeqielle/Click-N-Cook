<?php
session_start();
include('../../lang/lang.php');

if($_SESSION['lang'] == 'FR') {
    include('../../lang/fr-lang.php');
} else {
    include('../../lang/en-lang.php');
}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo TXT_INDEX_PROPOS; ?></title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
        <link rel="stylesheet" type="text/css" href="./main.css">
		<link rel="shortcut icon" href="/images/logo.png ">
    </head>
    <body>
        <div id="overlay">
			<div>
				<button id="startButton"><?php echo TXT_WEBGL_CLICK; ?></button>
			</div>
        </div>
        
        <video id="video" playsinline loop crossOrigin="anonymous" webkit-playsinline style="display:none">
			<source src="/WebGLVideo/video.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'>
        </video>

		<script type="module">
			import * as THREE from '/WebGLBuild/three.module.js';
			import Stats from './jsm/libs/stats.module.js';
			import { GLTFLoader } from './jsm/loaders/GLTFLoader.js';
            import { EffectComposer } from './jsm/postprocessing/EffectComposer.js';
			import { RenderPass } from './jsm/postprocessing/RenderPass.js';

            var container;

			var mesh, goal;
			var temp = new THREE.Vector3;

            var video, texture, material, meshVideo;

			var stats, clock, light, camera, scene, renderer;
			var door, truck, crate;

            var composer;

			var materials;

			var startButton = document.getElementById( 'startButton' );
			startButton.addEventListener( 'click', function () {

				init();
				animate();

			}, false );

			function init() {
				clock = new THREE.Clock();

                var overlay = document.getElementById( 'overlay' );
				overlay.remove();
				
				container = document.createElement( 'div' );
				document.body.appendChild( container );

				camera = new THREE.PerspectiveCamera(70, window.innerWidth / window.innerHeight, 0.5, 10000);
    			camera.position.set( 46, 4.8, 158.2);
				
				scene = new THREE.Scene();

				var path = "./textures/cube/skyboxsun25deg/";
				var format = '.jpg';
				var urls = [
					path + 'px' + format, path + 'nx' + format,
					path + 'py' + format, path + 'ny' + format,
					path + 'pz' + format, path + 'nz' + format
				];

				var textureCube = new THREE.CubeTextureLoader().load(urls);
				scene.background = textureCube;
                
				var loader = new GLTFLoader();
				var loaderTexture = new THREE.TextureLoader();

				loader.load('./projet/ground/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.5, 0.1, 0.5);
						gltf.scene.position.set(0, 0, 0);
						
						for (let j = -100; j <= 236; j = j + 67) {
							for (let i = -150; i < 200; i = i + 114) {
								let tree2 = gltf.scene.clone();
								tree2.position.x = j;
								tree2.position.z = i;
								scene.add(tree2);
							}
						}
					},
				);

				loader.load('./projet/moutain/untitled.gltf', function(gltf) {
							gltf.scene.scale.set(0.7, 0.3, 0.2);
							gltf.scene.position.x = 220;
							gltf.scene.position.y = -2;
							gltf.scene.position.z = -70	;
							gltf.scene.rotation.y = 3.05;

							scene.add(gltf.scene);
						},

				);

				loader.load('./projet/moutain1/untitled.gltf', function(gltf) {
							gltf.scene.scale.set(0.2, 0.3, 0.9);
							gltf.scene.position.x = 200;
							gltf.scene.position.y = -2;
							gltf.scene.position.z = -220;

							scene.add(gltf.scene);


							let moutain1Bis = gltf.scene.clone();
							moutain1Bis.position.x = -225;
							scene.add(moutain1Bis);

							let moutain1Bis1 = gltf.scene.clone();
							moutain1Bis1.scale.set(0.2, 0.3, 0.4);
							moutain1Bis1.position.x = 180;
							moutain1Bis1.rotation.y = -1.6;
							moutain1Bis1.position.z = 210;
							scene.add(moutain1Bis1);

						},

				);

				loader.load('./projet/moutain3/untitled.gltf', function(gltf) {
							gltf.scene.scale.set(0.2, 0.3, 0.3);
							gltf.scene.position.x = 0;
							gltf.scene.position.y = -2;
							gltf.scene.position.z = 220;
							gltf.scene.rotation.y = -0.6;

							scene.add(gltf.scene);

							let moutain3Bis = gltf.scene.clone();
							moutain3Bis.position.x = 240;
							moutain3Bis.rotation.y = 0.6;
							moutain3Bis.position.z = 140;
							scene.add(moutain3Bis);
						},

				);

				loader.load('./projet/roads/road/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(10, 3, 11);
						gltf.scene.position.set(160, 4, 0);
						gltf.scene.rotation.y = Math.PI / 2;
						
						let i;

						for (i = -150; i < 200; i = i + 26.6) {
							let road = gltf.scene.clone();
							road.position.z = i;
							scene.add(road);
						}

						for (i = -150; i < 200; i = i + 26.6) {
							let road1 = gltf.scene.clone();
							road1.position.z = i;
							road1.position.x = 348.8;
							scene.add(road1);
						}

						for (i = 19; i < 153; i = i + 26.6) {
							let road2 = gltf.scene.clone();
							road2.scale.set(10, 3, 10);
							road2.rotation.y = 0;
							road2.position.x = i;
							road2.position.z = 352.63;
							scene.add(road2);
						}

						for (i = 36; i < 63; i = i + 26.6) {
							let road3 = gltf.scene.clone();
							road3.scale.set(10, 3, 10);
							road3.rotation.y = 0;
							road3.position.x = -i;
							road3.position.z = 352.63;
							scene.add(road3);
						}

						for (i = 19; i < 153; i = i + 26.6) {
							let road4 = gltf.scene.clone();
							road4.scale.set(11, 3, 11);
							road4.rotation.y = 0;
							road4.position.x = i;
							road4.position.z = -32.8;
							scene.add(road4);
						}

						let road5 = gltf.scene.clone();
						road5.scale.set(10, 3, 10);
						road5.rotation.y = Math.PI/2;
						road5.position.x = 65.58;
						road5.position.z = 195.3;
						scene.add(road5);
					},
				);

				loader.load('./projet/roads/corner/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(11, 3, 10);
						gltf.scene.position.set(173.2, 4, 38.43);
						gltf.scene.rotation.y = - Math.PI;
						
						scene.add(gltf.scene);

						let corner1 = gltf.scene.clone();
						corner1.scale.set(11, 3, 11);
						corner1.position.x = 3.17;
						corner1.position.z = -171.2;
						corner1.rotation.y = -Math.PI/2;
						scene.add(corner1);

						let corner2 = gltf.scene.clone();
						corner2.scale.set(11, 3, 11);
						corner2.position.x = 21.62;
						corner2.position.z = -1.2;
						corner2.rotation.y = Math.PI;
						scene.add(corner2);

						let corner3 = gltf.scene.clone();
						corner3.scale.set(10, 3, 10);
						corner3.position.x = 94.3;
						corner3.position.z = 193;
						corner3.rotation.y = Math.PI/2;
						scene.add(corner3);
					},
				);

				loader.load('./projet/roads/3croisements/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(10, 3, 11);
						gltf.scene.position.set(160.15, 4, 193);
						gltf.scene.rotation.y = Math.PI / 2;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/pavé/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(3, 3, 3);
						gltf.scene.position.set(-76, 0, 125);
						gltf.scene.rotation.y = -Math.PI / 2;
						
						scene.add(gltf.scene);
					},
				);


				loader.load('./projet/foutain/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(1, 1, 1);
						gltf.scene.position.set(-76, 2, 125);
						gltf.scene.rotation.y = -Math.PI / 2;
						
						scene.add(gltf.scene);
					},
				);
               
                loader.load('./projet/house/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(1.5, 1.5, 1.5);
						gltf.scene.position.set(35, 0.9, 120);
						gltf.scene.rotation.y = Math.PI * 1.5;
						
						scene.add(gltf.scene);
					},
				);

				door = new THREE.Object3D();
				loader.load('./projet/door/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(1.5, 1.5, 1.5);
						
						door.add(gltf.scene);
						door.position.set(35, 0.9, 120);
						door.rotation.y = Math.PI * 1.5;
						scene.add(door);
					},
				);
					
				loader.load('./projet/TV/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.5, 0.5, 0.5);
						gltf.scene.position.set(37, 2.35, 130);
						gltf.scene.rotation.y = Math.PI;
						

						scene.add(gltf.scene);
					},
				);
				
				
				truck = new THREE.Object3D();
				loader.load('./projet/truck/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(3.7, 3.7, 3.7);
						
						truck.add(gltf.scene);
						truck.position.set(39, 0.9, 185);
						truck.rotation.y = Math.PI * 2.31;
						scene.add(truck);
					},
				);
				
				crate = new THREE.Object3D();
				loader.load('./projet/crate/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.1, 0.1, 0.1);
						
						crate.add(gltf.scene);
						crate.position.set(155, 1.1, 165);
						crate.rotation.y = 0;
						scene.add(crate);
					},
				);
					
				loader.load('./projet/truck2/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.05, 0.05, 0.05);
						gltf.scene.position.set(81, 0.4, 160);
						gltf.scene.rotation.y = Math.PI * 2.34;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/building1/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(7, 7, 7);
						gltf.scene.position.set(40, 0.9, 45);
						gltf.scene.rotation.y = Math.PI;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/building2/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(4, 4, 4);
						gltf.scene.position.set(-10, 3.9, -30);
						gltf.scene.rotation.y = Math.PI * 1.5;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/building3/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.7, 0.7, 0.7);
						gltf.scene.position.set(310, 0.8, -20);
						gltf.scene.rotation.y = Math.PI * 0.5;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/building4/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.5, 0.5, 0.5);
						gltf.scene.position.set(-30, 0.3, -200);
						gltf.scene.rotation.y = Math.PI * 1.5;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/building5/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.5, 0.5, 0.5);
						gltf.scene.position.set(-35, 0.3, -160);
						gltf.scene.rotation.y = Math.PI;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/building6/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.2, 0.2, 0.2);
						gltf.scene.position.set(157.65, 0.3, -20);
						gltf.scene.rotation.y = Math.PI * 0.5;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/building7/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.25, 0.25, 0.25);
						gltf.scene.position.set(150, 0.3, -110);
						gltf.scene.rotation.y = Math.PI * 0.5;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/warehouse/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(20, 20, 20);
						gltf.scene.position.set(115, -0.2, 177.2);
						gltf.scene.rotation.y = 0;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/truckPark/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(1.5, 1.5, 1.5);
						gltf.scene.position.set(40, 0.05, 181.95);
						gltf.scene.rotation.y = Math.PI * 2;
						
						scene.add(gltf.scene);
					},
				);

				loader.load('./projet/bench1/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(1.5, 1.5, 1.5);
						gltf.scene.position.set(-50, 2.2, 93);
						gltf.scene.rotation.y = -Math.PI / 2.3;
						
						scene.add(gltf.scene);

						let bench1Bis = gltf.scene.clone();
						bench1Bis.position.x = -58;
						bench1Bis.position.z = 143;
						bench1Bis.rotation.y = -3.9;
						scene.add(bench1Bis);
					},
				);

				loader.load('./projet/bench2/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(1.5, 1.5, 1.5);
						gltf.scene.position.set(-45, 2.2, 103);
						gltf.scene.rotation.y = -Math.PI / 1.7;
						
						scene.add(gltf.scene);

						let bench2Bis = gltf.scene.clone();
						bench2Bis.position.x = -92;
						bench2Bis.position.z = 95;
						bench2Bis.rotation.y = 3.5;
						scene.add(bench2Bis);
					},
				);

				loader.load('./projet/table/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(0.25, 0.25, 0.25);
						gltf.scene.position.set(-43, 0.5, 149);
						gltf.scene.rotation.y = -Math.PI / 2;
						
						scene.add(gltf.scene);

						let table1 = gltf.scene.clone();
						table1.position.x = -45;
						table1.position.z = 135;
						scene.add(table1);
					},
				);

				loader.load('./projet/bitum/untitled.gltf', function(gltf) {
						gltf.scene.scale.set(31.35, 5, 20.3);
						gltf.scene.position.set(-45, 0.37, 73);
						gltf.scene.rotation.y = Math.PI * 1.5;
						
						scene.add(gltf.scene);
					},
				);


				//light
				light = new THREE.PointLight(0xc4c4c4, 1);
				light.position.set(160, 200, 100);
				light.castShadow = true;
				scene.add(light);

				var ambientLight = new THREE.AmbientLight(0xcccccc, 0.4);
				scene.add(ambientLight);

				var directionalLight = new THREE.DirectionalLight(0xffffff, 1);
				directionalLight.position.x = -30;
				directionalLight.position.y = 200;
				directionalLight.position.z = 100;
				directionalLight.castShadow = true;
				scene.add(directionalLight);

				var geometry = new THREE.BoxGeometry( 1, 1, 1);

				mesh = new THREE.Mesh();
				mesh.position.set(37, 4, 146.7);
				
				goal = new THREE.Object3D;
				var group = new THREE.Group();
				
				mesh.add( goal );
				scene.add( mesh );
				
				goal.position.set(0, 0, -20);

				//video
                video = document.getElementById('video');
				video.play();

				texture = new THREE.VideoTexture(video);

                var geometry;

				var parameters = {color: 0xffffff, map: texture};

				geometry = new THREE.BoxBufferGeometry(200, 100, 100);

				materials = new THREE.MeshLambertMaterial(parameters);

				meshVideo = new THREE.Mesh(geometry, materials);

				meshVideo.position.set(37, 4.26, 129.9);

				meshVideo.scale.set(0.035, 0.035, 0.001);

				scene.add(meshVideo);


				//renderer
				renderer = new THREE.WebGLRenderer();
				renderer.setSize(window.innerWidth, window.innerHeight);
				container.appendChild(renderer.domElement);
				stats = new Stats();
			}

			function animate() {
				requestAnimationFrame(animate);

				stats.update();

                if(door.rotation.y > 4.6) {
                    door.position.z += 0.85;
                    door.position.x -= 0.1;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 4.5) {
                    door.position.z += 0.9;
                    door.position.x -= 0.2;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 4.4) {
                    door.position.z += 0.95;
                    door.position.x -= 0.35;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 4.3) {
                    door.position.z += 0.8;
                    door.position.x -= 0.4;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 4.2) {
                    door.position.z += 0.79;
                    door.position.x -= 0.4;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 4.1) {
                    door.position.z += 0.78;
                    door.position.x -= 0.6;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 4) {
                    door.position.z += 0.68;
                    door.position.x -= 0.57;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 3.9) {
                    door.position.z += 0.55;
                    door.position.x -= 0.65;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 3.8) {
                    door.position.z += 0.52;
                    door.position.x -= 0.7;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 3.7) {
                    door.position.z += 0.5;
                    door.position.x -= 0.75;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 3.6) {
                    door.position.z += 0.4;
                    door.position.x -= 0.8;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 3.5) {
                    door.position.z += 0.32;
                    door.position.x -= 1;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 3.4) {
                    door.position.z += 0.22;
                    door.position.x -= 0.8;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 3.3) {
                    door.position.z += 0.12;
                    door.position.x -= 0.9;
                    door.rotation.y -= 0.1;
                } else if(door.rotation.y > 3.2) {
                    door.position.z += 0.02;
                    door.position.x -= 0.9;
                    door.rotation.y -= 0.1;
				}

				

				if(mesh.position.x == 37 && mesh.position.z == 146.7) {
					setTimeout(function(){
						if(mesh.position.x == 37 && mesh.position.z == 146.7) {
                    		mesh.position.z -= 1;
						}
					},31000);
				}

				if(mesh.position.z <= 145.7 && mesh.position.z > 137.8 && mesh.position.x == 37) {
					mesh.position.z -= 0.125;
				}

				if(mesh.position.z == 137.7 && mesh.rotation.y == 0) {
					mesh.rotation.y = -0.1;
					mesh.position.x -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 137.7 && mesh.rotation.y == -0.1) {
					mesh.rotation.y = -0.2;
					mesh.position.x -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 137.7 && mesh.rotation.y == -0.2) {
					mesh.rotation.y = -0.3;
					mesh.position.x -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 137.7 && mesh.rotation.y == -0.3) {
					mesh.rotation.y = -0.4;
					mesh.position.x -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 137.7 && mesh.rotation.y == -0.4) {
					mesh.rotation.y = -0.5;
					mesh.position.x -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 137.7 && mesh.rotation.y == -0.5) {
					mesh.rotation.y = -0.6;
					mesh.position.x -= 1;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 137.7 && mesh.rotation.y == -0.6) {
					mesh.rotation.y = -0.7;
					mesh.position.z -= 1;
					mesh.position.x -= 1;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 136.7 && mesh.rotation.y == -0.7) {
					mesh.rotation.y = -0.8;
					mesh.position.z -= 1;
					mesh.position.x -= 1;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 135.7 && mesh.rotation.y == -0.8) {
					mesh.rotation.y = -0.9;
					mesh.position.x -= 1;
					mesh.position.z -= 1;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 134.7 && mesh.rotation.y == -0.9) {
					mesh.rotation.y = -1.0;
					mesh.position.x -= 1;
					mesh.position.z -= 1;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 133.7 && mesh.rotation.y == -1) {
					mesh.rotation.y = -1.1;
					mesh.position.x -= 1;
					mesh.position.z -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 131.7 && mesh.rotation.y == -1.1) {
					mesh.rotation.y = -1.2;
					mesh.position.z -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 129.7 && mesh.rotation.y == -1.2) {
					mesh.rotation.y = -1.3;
					mesh.position.x -= 1;
					mesh.position.z = 127.7;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 127.7 && mesh.rotation.y == -1.3) {
					mesh.rotation.y = -1.4;
					mesh.position.x -= 1;
					mesh.position.z -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 125.7 && mesh.rotation.y == -1.4) {
					mesh.rotation.y = -1.5;
					mesh.position.x -= 1;
					mesh.position.z -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 123.7 && mesh.rotation.y == -1.5) {
					mesh.rotation.y = -1.57;
					mesh.position.x -= 1;
					mesh.position.z -= 2;
					mesh.position.y += 0.1;
				} else if(mesh.position.z == 121.7 && mesh.rotation.y == -1.57 && mesh.position.x > 4) {					
					mesh.position.x -= 0.1;
				} else if(mesh.position.x <= 25 && mesh.rotation.y <= 0) {
					mesh.rotation.y += 0.015;
					mesh.position.z += 0.3;
					mesh.position.x += 0.125;
				} else if(mesh.position.z <= 200 && mesh.position.z >= 153.1  && mesh.rotation.y <= 0.1 && mesh.rotation.y >= 0) {
					mesh.position.z += 0.2;
				} else if(mesh.position.x <= 50 && mesh.rotation.y <= 1.57 && mesh.rotation.y > 0) {
					mesh.rotation.y += 0.015;
					mesh.position.x += 0.25;
				} else if(mesh.position.x <= 100 && mesh.rotation.y <= 3.14 && mesh.rotation.y >= 1.57) {
					mesh.rotation.y += 0.015;
					mesh.position.z -= 0.2;
					mesh.position.x += 0.01;
				} else if(mesh.position.x <= 100 && mesh.rotation.y <= 4.71 && mesh.rotation.y >= 3.14) {
					mesh.rotation.y += 0.015;
					mesh.position.x -= 0.15;
					mesh.position.z += 0.1;
				} else if(mesh.position.x <= 100 && mesh.rotation.y <= 6.28 && mesh.rotation.y >= 4.71) {
					mesh.rotation.y += 0.015;
					mesh.position.z += 0.175;
					mesh.position.x += 0.0785;
					mesh.position.y += 0.01;
				} else if(truck.rotation.y > 7.2 && truck.rotation.y <= 8.85 && mesh.rotation.y > 6.2 && mesh.rotation.y <= 7.85 && mesh.position.x < 100) {
					goal.position.z -= 0.25;
					goal.position.y += 0.12;
					mesh.rotation.y += 0.015;
					mesh.position.z += 0.068;
					mesh.position.x += 0.321;
					truck.rotation.y += 0.015;
					truck.position.z += 0.27;
					truck.position.x += 0.0785;
				} else if(mesh.rotation.y > 7.86 && mesh.rotation.y <= 7.865 && truck.rotation.y > 8.83 && truck.rotation.y <= 8.835 && mesh.position.x <= 174) {
					truck.position.x += 0.4;
					mesh.position.x += 0.4;
				} else if(truck.rotation.y > 7.2 && truck.rotation.y <= 8.85 && mesh.rotation.y > 6.2 && mesh.rotation.y <= 7.87 && mesh.position.x <= 191) {
					goal.position.z += 0.241;
					goal.position.y -= 0.119;
					mesh.rotation.y -= 0.015;
					mesh.position.z -= 0.0775;
					mesh.position.x -= 0.326;
					truck.rotation.y -= 0.015;
					truck.position.z -= 0.27;
					truck.position.x -= 0.0785;
				} else if(mesh.rotation.y <= 7.77 && truck.rotation.y > 7.19 && truck.rotation.y < 7.2 && truck.position.x > 142.28 && truck.position.x < 142.29 && truck.position.z > 183.91 && truck.position.z < 183.92) {
					mesh.rotation.y += 0.015;
					mesh.position.z -= 0.1;
					mesh.position.x += 0.12;
					mesh.position.y -= 0.01;
				} else if(mesh.rotation.y >= 7.77 && mesh.rotation.y <= 9.42 && truck.rotation.y > 7.19 && truck.rotation.y < 7.2 && truck.position.x > 142.28 && truck.position.x < 142.29 && truck.position.z > 183.91 && truck.position.z < 183.92) {
					mesh.rotation.y += 0.015;
					mesh.position.z -= 0.2;
					mesh.position.x += 0.03;
				} else if(mesh.rotation.y > 9.42 && mesh.position.z <= 174.7 && mesh.position.z >= 150 && mesh.position.x <= 154.24 && mesh.position.x >= 154.23) {
					mesh.position.z -= 0.2;
				} else if(mesh.rotation.y > 9.42 && mesh.position.z <= 150 && mesh.position.z >= 147 && mesh.position.x <= 154.24 && mesh.position.x >= 154.23) {
					mesh.position.z -= 0.1;
					mesh.position.y -= 0.1;
				} else if(mesh.rotation.y >= 9.42 && mesh.rotation.y < 10.995 && mesh.position.z <= 180.63 && mesh.position.z >= 146 && mesh.position.x <= 154.24 && mesh.position.x >= 138.93) {
					mesh.rotation.y += 0.015;
					mesh.position.z += 0.33;
					mesh.position.x -= 0.15;
					mesh.position.y += 0.04;
					crate.rotation.y += 0.015;
					crate.position.z += 0.15;
					crate.position.x += 0.015;
					crate.position.y += 0.0425;
				} else if(crate.rotation.y < 1.55 && crate.position.z <= 180.46 && crate.position.z >= 180.45 && crate.position.x <= 156.55 && crate.position.x >= 146) {
					crate.position.x -= 0.2;
					mesh.position.x -= 0.2;
				} else if(truck.position.z > 183.91 && truck.position.z < 183.92 && mesh.rotation.y < 12.56 && crate.rotation.y < 1.55 && crate.position.z <= 180.46 && crate.position.z >= 180.45 && crate.position.x <= 145.95 && crate.position.x >= 145.94 && crate.position.y > 5.47) {
					mesh.rotation.y += 0.015;
					mesh.position.z += 0.246;
					mesh.position.x += 0.106;
					goal.position.y += 0.119;
					goal.position.z -= 0.241;
				} else if(truck.rotation.y < 7.20 && truck.rotation.y >= 5.73 && truck.position.z >= 183.91 && truck.position.z < 300 && mesh.rotation.y < 12.57 && mesh.rotation.y >= 10.99 && crate.rotation.y < 1.55 && crate.rotation.y >= -0.03) {
					goal.position.z -= 0.02;
					mesh.rotation.y -= 0.01535;
					mesh.position.z -= 0.02;
					mesh.position.x -= 0.287;
					truck.rotation.y -= 0.015;
					truck.position.z += 0.23;
					truck.position.x -= 0.0785;
					crate.rotation.y -= 0.015;
					crate.position.z += 0.3;
					crate.position.x -= 0.0785;
				} else if(crate.position.z < 209.86 && crate.position.z > 209.85 && truck.position.z > 206.45 && truck.position.z < 206.46 && mesh.position.z > 205.07 && mesh.position.z < 205.08 && mesh.position.x > -90) {
					mesh.position.x -= 0.4;
					truck.position.x -= 0.4;
					crate.position.x -= 0.4;
				} else if(truck.rotation.y < 5.73 && truck.rotation.y > 4.13 && crate.position.x < -63 && crate.position.z > 200) {
					mesh.rotation.y -= 0.01535;
					mesh.position.z -= 0.27;
					mesh.position.x += 0.17;
					truck.rotation.y -= 0.015;
					truck.position.z -= 0.07;
					truck.position.x -= 0.07;
					crate.rotation.y -= 0.015;
					crate.position.z -= 0.07;
					crate.position.x -= 0.135;
				} else if(truck.rotation.y < 4.13 && truck.rotation.y > 4.12 && crate.position.x < -77 && truck.position.z > 155) {
					mesh.position.z -= 0.4;
					truck.position.z -= 0.4;
					crate.position.z -= 0.4;
				} else if(truck.rotation.y < 4.13 && truck.rotation.y >= 2.56 && crate.position.x < -63 && crate.position.z >= 135.2) {
					mesh.rotation.y -= 0.015;
					mesh.position.z += 0.09;
					mesh.position.x += 0.245;
					truck.rotation.y -= 0.015;
					truck.position.z -= 0.15;
					truck.position.x += 0.05;
					crate.rotation.y -= 0.015;
					crate.position.z -= 0.22;
					crate.position.x += 0.05;
				} else if(truck.rotation.y < 4.12 && truck.rotation.y >= 2.54 && crate.position.z < 135.27) {
					mesh.rotation.y += 0.015;
					mesh.position.z -= 0.39;
					mesh.position.x -= 0.137;
					truck.rotation.y += 0.015;
					truck.position.z -= 0.15;
					truck.position.x += 0.05;
					crate.rotation.y += 0.015;
					crate.position.z -= 0.07;
					crate.position.x += 0.048;
				} else if(truck.rotation.y < 5.77 && truck.rotation.y >= 4.12 && crate.position.z < 128) {
					mesh.rotation.y += 0.015;
					mesh.position.z += 0.1;
					mesh.position.x -= 0.341;
					truck.rotation.y += 0.015;
					truck.position.z -= 0.1;
					truck.position.x -= 0.1;
					crate.rotation.y += 0.015;
					crate.position.z -= 0.11;
					crate.position.x -= 0.01;
				} else if(truck.rotation.y < 7.34 && truck.rotation.y >= 5.77 && crate.position.x < -68.60) {
					goal.position.y -= 0.119;
					goal.position.z += 0.261;
					mesh.rotation.y += 0.015;
					mesh.position.z += 0.325;
					mesh.position.x -= 0.085;
					truck.rotation.y += 0.015;
					truck.position.z += 0.1;
					truck.position.x -= 0.3;
					crate.rotation.y += 0.015;
					crate.position.z += 0.02;
					crate.position.x -= 0.33;
				} else if(goal.position.z > -20.083 && goal.position.y < -0.252 && mesh.rotation.y < 14.2) {
					mesh.rotation.y += 0.015;
					mesh.position.z -= 0.005;
					mesh.position.x += 0.25;
				} else if(goal.position.z > -20.083 && goal.position.y < -0.252 && mesh.rotation.y >= 14.2 && mesh.rotation.y < 15.77) {
					mesh.rotation.y += 0.015;
					mesh.position.z -= 0.3;
					mesh.position.x -= 0.18;
				} else if(goal.position.z > -20.083 && goal.position.y < -0.252 && mesh.rotation.y >= 15.77 && mesh.rotation.y < 15.774 && mesh.position.z > 95) {
					mesh.position.z -= 0.2;
				} else if(goal.position.z > -20.083 && goal.position.y < -0.252 && mesh.rotation.y >= 15.77 && mesh.rotation.y < 17.34) {
					mesh.rotation.y += 0.015;
					mesh.position.z += 0.11;
					mesh.position.x -= 0.2;
				} else if(goal.position.z > -20.083 && goal.position.y < -0.252 && mesh.rotation.y >= 17.34 && mesh.rotation.y < 18.91) {
					mesh.rotation.y += 0.015;
					mesh.position.z += 0.25;
					mesh.position.x += 0.125;
				} else if(goal.position.z > -20.083 && goal.position.y < -0.252 && mesh.rotation.y >= 18.91 && mesh.rotation.y < 20.48) {
					mesh.rotation.y += 0.015;
					mesh.position.z -= 0.12;
					mesh.position.x += 0.21;
					mesh.position.y += 0.005;
				}
				
				temp.setFromMatrixPosition(goal.matrixWorld);
				
				camera.position.lerp(temp, 0.2);
				camera.lookAt( mesh.position );
				
				renderer.render(scene, camera);
			}
		</script>
	</body>
</html>