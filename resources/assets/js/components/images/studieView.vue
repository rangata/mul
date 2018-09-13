<template>
    <!--<v-layout>-->
    <v-container flex>
        <!--<v-navigation-drawer-->
            <!--right-->
            <!--fixed-->
            <!--app-->
            <!--clipped-right-->
            <!--dark-->
            <!--v-model="dar"-->
        <!--&gt;-->
        <!--</v-navigation-drawer>-->
         <v-content>
             <!--<div class="dropdown">-->
                 <!--<button id="toolSelector" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select tool <span class="caret"></span></button>-->

                     <!--<li><a id="stackScroll" class="list-group-item">Stack Scroll</a></li>-->
                     <!--&lt;!&ndash;<li><a id="length" class="list-group-item">Length</a></li>&ndash;&gt;-->
                     <!--&lt;!&ndash;<li><a id="circleroi" class="list-group-item">Ellipse</a></li>&ndash;&gt;-->
                     <!--&lt;!&ndash;<li><a id="rectangleroi" class="list-group-item">Rectangle</a></li>&ndash;&gt;-->
                     <!--&lt;!&ndash;<li><a id="angle" class="list-group-item">Angle</a></li>&ndash;&gt;-->
                 <!--<a id="clearToolData" class="btn btn-secondary">Clear Tools</a>-->
                 <!--<a id="resetViewport" class="btn btn-secondary">Reset View</a>-->
                 <!--<a id="fullscreen" class="btn btn-secondary">Fullscreen</a>-->
             <!--</div>-->
             <v-btn-toggle v-model="toggle_exclusive">
                 <v-btn id="enableWindowLevelTool">Контраст</v-btn>
                 <v-btn id="length">Измерване</v-btn>
                 <v-btn id="pan">Пан</v-btn>
                 <v-btn id="rotate">Завъртане</v-btn>
                 <v-btn id="zoom">Приближаване(щип)</v-btn>
                 <v-btn id="zoomDrag">Приближаване(с провлачване на трите пръста)</v-btn>
                 <v-btn id="probe">Сонда</v-btn>
                 <v-btn color="danger" id="clearToolData">Изчисти всичко</v-btn>

             </v-btn-toggle>
         </v-content>

            <!--orign-->
            <!--style="width:100%;height:100%;min-height:256px;min-width:256px;position:relative;display:inline-block;color:white;"-->
        <div
                 oncontextmenu="return false"
                 class='cornerstone-enabled-image'
                 unselectable='on'
                 onselectstart='return false;'
                 onmousedown='return false;'>
                    <div id="dicomImage" ref="dicomImage"
                         style="width:100%;height:100%;min-height:250px;min-width:350px;">
                    </div>
            </div>
            <v-content grid-list-md>
                <v-layout row wrap>
                        <v-card v-for="(img, key) in this.images" :key="img.ID">
                            <img :src="stack(img)" height="200">
                            <v-card-actions>
                                <v-btn @click="enableImage(instances[key].ID)" color="green">
                                    Позволи редакция!
                                </v-btn>
                            </v-card-actions>
                        </v-card>
                </v-layout>
            </v-content>
    </v-container>
    <!--</v-layout>-->
</template>

<script>
    import Hammer from 'hammerjs';
    import * as cornerstone from 'cornerstone-core';
    import * as cornerstoneMath from 'cornerstone-math';
    import * as cornerstoneWebImageLoader from 'cornerstone-web-image-loader';
    import * as cornerstoneTools from 'cornerstone-tools';


    cornerstoneTools.external.Hammer = Hammer;
    cornerstoneTools.external.cornerstone = cornerstone;
    cornerstoneWebImageLoader.external.cornerstone = cornerstone;

    cornerstoneTools.external.cornerstoneMath = cornerstoneMath;


    export default {
        name: "studieView",

        data() {
            return {
                studieId: this.$route.params.studieId,
                instances: [],
                loading: true,
                renderImage: true,
                images: [],
                dar: false,
                text: 'center',
                icon: 'justify',
                toggle_none: null,
                toggle_one: 0,
                toggle_exclusive: 2,
                toggle_multiple: [0, 1, 2]
            }
        },
        mounted() {
            console.log(this.$store.state)
            axios.get('/api/studies/' + this.$route.params.studieId +'/instances')
                .then(
                    (data) => {
                        this.instances = data.data
                        data.data.forEach(instance => {
                            this.loading = true
                           axios.get('/api/instances/' + instance.ID + '/preview', { responseType: 'arraybuffer'})
                               .then(

                                   (data) => {
                                       this.images.push(data.data)
                                       this.loading = false
                                   }
                               )
                        })
                    }
                )
                .catch(
                    (er) => console.log(er)
                )
        },
        methods: {
            enableImage(key) {
            this.renderImage = true
              let element = this.$refs.dicomImage

                cornerstone.enable(element);

                // // Listen for changes to the viewport so we can update the text overlays in the corner
                // function onImageRendered(e) {
                //     var viewport = cornerstone.getViewport(e.target);
                //     document.getElementById('mrbottomleft').textContent = "WW/WC: " + Math.round(viewport.voi.windowWidth) + "/" + Math.round(viewport.voi.windowCenter);
                //     document.getElementById('mrbottomright').textContent = "Zoom: " + viewport.scale.toFixed(2);
                // };
                //
                // element.addEventListener('cornerstoneimagerendered', onImageRendered);
                var stack = {
                    currentImageIdIndex : 0,
                    imageIds: element
                };

                var config = {
                    // invert: true,
                    minScale: 0.25,
                    maxScale: 20.0,
                    preventZoomOutsideImage: true
                };

                cornerstoneTools.zoom.setConfiguration(config);


                var configuration = {
                    testPointers: function(eventData) {
                        return (eventData.numPointers >= 3);
                    }
                };
                cornerstoneTools.panMultiTouch.setConfiguration(configuration);


                cornerstone.loadImage('https://192.168.100.7/api/instances/'+ key +'/preview').then(function(image) {
                    cornerstone.displayImage(element, image);

                    cornerstoneTools.touchInput.enable(element);
// Set the stack as tool state
                    cornerstoneTools.addStackStateManager(element, ['stack']);
                    cornerstoneTools.addToolState(element, 'stack', stack);
                    // Enable all tools we want to use with this element
                    cornerstoneTools.zoomTouchPinch.activate(element);
                    //cornerstoneTools.rotateTouch.activate(element);
                    cornerstoneTools.wwwcTouchDrag.activate(element);
                    cornerstoneTools.panMultiTouch.activate(element);

                    // helper function used by the tool button handlers to disable the active tool
                    // before making a new tool active
                    function disableAllTools() {
                        cornerstoneTools.panTouchDrag.deactivate(element);
                        cornerstoneTools.rotateTouchDrag.deactivate(element);
                        cornerstoneTools.rotateTouch.disable(element);
                        cornerstoneTools.ellipticalRoiTouch.deactivate(element);
                        cornerstoneTools.angleTouch.deactivate(element);
                        cornerstoneTools.rectangleRoiTouch.deactivate(element);
                        cornerstoneTools.lengthTouch.deactivate(element);
                        cornerstoneTools.probeTouch.deactivate(element);
                        cornerstoneTools.zoomTouchDrag.deactivate(element);
                        cornerstoneTools.wwwcTouchDrag.deactivate(element);
                        cornerstoneTools.stackScrollTouchDrag.deactivate(element);
                    }

                    // Tool button event handlers that set the new active tool

                    document.getElementById('enableWindowLevelTool').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.wwwcTouchDrag.activate(element);

                    });
                    document.getElementById('pan').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.panTouchDrag.activate(element);
                    });
                    document.getElementById('rotate').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.rotateTouchDrag.activate(element);

                    });
                    document.getElementById('zoom').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.zoomTouchPinch.activate(element);

                    });
                    document.getElementById('zoomDrag').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.zoomTouchDrag.activate(element);
                    });
                    document.getElementById('stackScroll').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.stackScrollTouchDrag.activate(element);

                    });


                    document.getElementById('length').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.lengthTouch.activate(element);

                    });
                    document.getElementById('probe').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.probeTouch.activate(element);
                    });
                    document.getElementById('circleroi').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.ellipticalRoiTouch.activate(element);
                    });
                    document.getElementById('rectangleroi').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.rectangleRoiTouch.activate(element);
                    });
                    document.getElementById('angle').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.angleTouch.activate(element);
                    });
                    document.getElementById('clearToolData').addEventListener('click', function() {
                        disableAllTools();
                        cornerstoneTools.wwwcTouchDrag.activate(element);
                        cornerstoneTools.zoomTouchPinch.activate(element);
                        var toolStateManager = cornerstoneTools.globalImageIdSpecificToolStateManager;
                        // Note that this only works on ImageId-specific tool state managers (for now)
                        toolStateManager.clear(element);
                        cornerstone.updateImage(element);
                    });
                    document.getElementById('resetViewport').addEventListener('click', function() {
                        cornerstone.reset(element);
                    });

            })
                this.renderImage = false;

            },
            messureDistance() {
                disableAllTools();
                cornerstoneTools.lengthTouch.activate(this.$refs.dicomImage);
            },
            getImage(id) {
                axios.get('/api/instances/' + id + '/preview')
                    .then(
                        (data) => {
                            return data.data
                            this.loading = false

                        })

            },
            stack(data) {
                return this.encodeImage(data);
            },
            encodeImage(arrayBuffer) {
                let u8 = new Uint8Array(arrayBuffer)
                let b64encoded = btoa([].reduce.call(new Uint8Array(arrayBuffer),function(p,c){return p+String.fromCharCode(c)},''))
                let mimetype="image/png"
                return "data:"+mimetype+";base64,"+b64encoded
            },
        }
    }
</script>

<style scoped>

</style>