<template>
 <v-container>
         <vue-qr-reader v-on:code-scanned="codeArrived"></vue-qr-reader>
         <v-content>
             <v-content class="images">
                 <div v-for="(image, key) in instImages">

                     <v-content class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                         <v-content class="thumbnail">
                             <img :src="image" class="image-responsive" style="width: 300px; height: 150px;">
                             <v-content class="caption">
                                 <h3>{{ patientInfo[0].value}}</h3>
                                 <p>
                                     От дата:
                                     <!--{{ instances[key].MainDicomTags.InstanceCreationDate ? ((instances[key].MainDicomTags.InstanceCreationDate |  moment("DD.MM.YYYY")) ) :"nqma"}}-->

                                 </p>
                                 <p>
                                     Час:
                                     <!--{{ instances[key].MainDicomTags.InstanceCreationTime ? (instances[key].MainDicomTags.InstanceCreationTime |  moment("DD.MM.YYYY")) : "Няма информация за часа на създаване!" }}-->
                                 </p>
                                 <p>
                                     <a href="#" class="btn btn-primary" v-on:click="enableImage(instances[key].ID)">Action</a>
                                     <a href="#" class="btn btn-default">Action</a>
                                 </p>
                             </v-content>
                         </v-content>
                     </v-content>

                 </div>

             </v-content>
             <v-row cols>
                 <!--// viewer-->
                 <div>
                     <!--<div class="row">-->
                         <!--<div class="col-lg-12 col-md-12 col-xs-12 col-md-offset-0">-->
                             <!--<div class="dropdown">-->
                                 <!--<button id="toolSelector" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Select tool <span class="caret"></span></button>-->
                                 <!--<ul class="dropdown-menu">-->
                                     <!--<li class="dropdown-header">Tools</li>-->
                                     <!--<li><a id="enableWindowLevelTool" class="list-group-item">WW/WC</a></li>-->
                                     <!--<li><a id="pan" class="list-group-item">Pan</a></li>-->
                                     <!--<li><a id="rotate" class="list-group-item">Rotate</a></li>-->
                                     <!--<li><a id="zoom" class="list-group-item">Zoom (pinch)</a></li>-->
                                     <!--<li><a id="zoomDrag" class="list-group-item">Zoom (drag)</a></li>-->
                                     <!--<li><a id="stackScroll" class="list-group-item">Stack Scroll</a></li>-->
                                     <!--<li><a id="length" class="list-group-item">Length</a></li>-->
                                     <!--<li><a id="probe" class="list-group-item">Probe</a></li>-->
                                     <!--<li><a id="circleroi" class="list-group-item">Ellipse</a></li>-->
                                     <!--<li><a id="rectangleroi" class="list-group-item">Rectangle</a></li>-->
                                     <!--<li><a id="angle" class="list-group-item">Angle</a></li>-->
                                 <!--</ul>-->
                                 <!--<a id="clearToolData" class="btn btn-secondary">Clear Tools</a>-->
                                 <!--<a id="resetViewport" class="btn btn-secondary">Reset View</a>-->
                                 <!--<a id="fullscreen" class="btn btn-secondary">Fullscreen</a>-->
                             <!--</div>-->
                         <!--</div>-->
                     <!--</div>-->
                     <br/>
                     <div class="row">
                         <div class="col-lg-12 col-md-12 col-xs-10 col-xs-offset-1 col-md-offset-0">
                             <div style="width:100%;height:100%;min-height:256px;min-width:256px;position:relative;display:inline-block;color:white;"
                                  oncontextmenu="return false"
                                  class='cornerstone-enabled-image'
                                  unselectable='on'
                                  onselectstart='return false;'
                                  onmousedown='return false;'>
                                 <div id="dicomImage" ref="dicomImage"
                                      style="width:100%;height:100%;min-height:256px;min-width:256px;">
                                 </div>
                                 <div id="mrtopleft" style="position: absolute;top:3px; left:3px">
                                     Patient Name
                                 </div>
                                 <div id="mrtopright" style="position: absolute;top:3px; right:3px">
                                     Hospital
                                 </div>
                                 <div id="mrbottomright" style="position: absolute;bottom:3px; right:3px">
                                     Zoom:
                                 </div>
                                 <div id="mrbottomleft" style="position: absolute;bottom:3px; left:3px">
                                     WW/WC:
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-xs-12 col-sm-6">
                 </div>
                 <div class="qr">
                 </div>
             </v-row>
         </v-content>
 </v-container>
</template>

<script>
    import VueQrReader from 'vue-qr-reader/dist/lib/vue-qr-reader.umd.js';

    import Hammer from 'hammerjs';
    import * as cornerstone from 'cornerstone-core';
    import * as cornerstoneMath from 'cornerstone-math';
    import * as cornerstoneWebImageLoader from 'cornerstone-web-image-loader';
    import * as cornerstoneTools from 'cornerstone-tools';
    import 'vue-qr-reader';

    cornerstoneTools.external.Hammer = Hammer;
    cornerstoneTools.external.cornerstone = cornerstone;
    cornerstoneWebImageLoader.external.cornerstone = cornerstone;

    cornerstoneTools.external.cornerstoneMath = cornerstoneMath;


    export default {
        name: "patientShow",
        props: ['instances', 'patientInfo'],
        components: {
            VueQrReader
        },

        data() {
            return {
                showLoupe: true,
                patient: {},
                instImages: []
            }


        },
        mounted() {

            for(let i = 0; i < this.$props.instances.length; i++) {

                this.getImage(this.$props.instances[i].ID).then((data) => this.instImages.push(data))
            }
        },
        methods: {
            // format time for instances
            formatTime(value) {

                let hours = value.slice(0, 2);
                let minutes = value.slice(2,4);
                let seconds = value.slice(4,6);

                return hours + ":" + minutes + ":" + seconds;
            },
            encodeImage(arrayBuffer) {
                let u8 = new Uint8Array(arrayBuffer)
                let b64encoded = btoa([].reduce.call(new Uint8Array(arrayBuffer),function(p,c){return p+String.fromCharCode(c)},''))
                let mimetype="image/png"
                return "data:"+mimetype+";base64,"+b64encoded
            },
            getImage(id) {
                return axios.get('/api/pat/' + id , { responseType: 'arraybuffer' })
                    .then((response) => {
                        let image = btoa(
                            new Uint8Array(response.data)
                                .reduce((data, byte) => data + String.fromCharCode(byte), '')
                        );
                        return `data:image/png;base64,${image}`;
                    });
            },
            enableImage(imageId) {
                var element = this.$refs.dicomImage;
                // console.log(this.instImages);

                // fakeemulatortouch();
                // Listen for changes to the viewport so we can update the text overlays in the corner
                function onImageRendered(e) {
                    var viewport = cornerstone.getViewport(e.target);
                    document.getElementById('mrbottomleft').textContent = "WW/WC: " + Math.round(viewport.voi.windowWidth) + "/" + Math.round(viewport.voi.windowCenter);
                    document.getElementById('mrbottomright').textContent = "Zoom: " + viewport.scale.toFixed(2);
                };
                element.addEventListener('cornerstoneimagerendered', onImageRendered);

                var container = element.parentNode;

                var onResize = function () {
                    if (document.fullscreenElement || document.mozFullScreenElement ||
                        document.webkitFullscreenElement || document.msFullscreenElement) {
                        container.offsetWidth = window.outerWidth;
                        container.offsetHeight = window.outerHeight;
                        element.offsetWidth = container.offsetWidth;
                        element.offsetHeight = container.offsetHeight;
                        cornerstone.resize(element, true);
                    }
                };
                window.addEventListener("resize", onResize);
                window.addEventListener("orientationchange", onResize);
                // image enable the dicomImage element
                cornerstone.enable(element);
                var configuration = {
                    testPointers: function (eventData) {
                        return (eventData.numPointers >= 3);
                    }
                };
                cornerstoneTools.panMultiTouch.setConfiguration(configuration);
                cornerstone.loadImage('http://192.168.100.7/api/instances/' + imageId + '/preview').then(function (image) {
                    cornerstone.displayImage(element, image);
                    // Set the stack as tool state
                    cornerstoneTools.addStackStateManager(element, ['stack']);
                    // cornerstoneTools.addToolState(element, 'stack', stack);
                    cornerstoneTools.touchInput.enable(element);
                    cornerstoneTools.mouseInput.enable(element);

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
                    var toolSelector = document.getElementById('toolSelector');
                    document.getElementById('enableWindowLevelTool').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.wwwcTouchDrag.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('pan').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.panTouchDrag.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('rotate').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.rotateTouchDrag.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('zoom').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.zoomTouchPinch.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('zoomDrag').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.zoomTouchDrag.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('stackScroll').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.stackScrollTouchDrag.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('length').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.lengthTouch.activate(element);
                        // toolSelector.textContent = e.currentTarget
                    });
                    document.getElementById('probe').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.probeTouch.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('circleroi').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.ellipticalRoiTouch.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('rectangleroi').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.rectangleRoiTouch.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('angle').addEventListener('click', function (e) {
                        disableAllTools();
                        cornerstoneTools.angleTouch.activate(element);
                        toolSelector.textContent = e.currentTarget.innerHTML
                    });
                    document.getElementById('clearToolData').addEventListener('click', function () {
                        disableAllTools();
                        cornerstoneTools.wwwcTouchDrag.activate(element);
                        cornerstoneTools.zoomTouchPinch.activate(element);
                        toolSelector.innerHTML = 'Select tool <span class="caret"></span>';
                        var toolStateManager = cornerstoneTools.globalImageIdSpecificToolStateManager;
                        // Note that this only works on ImageId-specific tool state managers (for now)
                        toolStateManager.clear(element);
                        cornerstone.updateImage(element);
                    });
                    document.getElementById('resetViewport').addEventListener('click', function () {
                        cornerstone.reset(element);
                    });

                });
            },
            codeArrived(event){
                alert(event)
            }
        }
    }
</script>

<style scoped>
    .image-canvas-wrapper {
        width: 100%;
        height: 80%;
        background: black;
    }
    .image-canvas {
        width: 100%;
        height: 100%;
    }
</style>