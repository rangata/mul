<template>
 <div class="container-fluid">

     <div class="images">
         <div v-for="instancesId in instances">
             <!--{{ instancesId.ID}}-->
             {{ getImage(instancesId.ID) }}
             <!--<img :src="getImage(instancesId.ID)" alt="">-->
         </div>

     </div>
     <div class="row">
         <!--<div class="col-xs-12 col-sm-2" v-if="this.showLoupe">-->
             <!--<ul class="list-group">-->
                 <!--<a id="activate" class="list-group-item">Activate</a>-->
                 <!--<a id="disable" class="list-group-item">Disable</a>-->
             <!--</ul>-->
             <!--<label for="magLevelRange">Magnification Level</label>-->
             <!--<input id="magLevelRange" type="range" min="1" value="5" max="10" />-->
             <!--<br/>-->
             <!--<label for="magSizeRange">Magnifying glass size</label>-->
             <!--<input id="magSizeRange" type="range" min="100" value="225" max="300" step="25"/>-->
         <!--</div>-->
         <div class="row">
             <div class="col-xs-2">
                 <ul class="list-group">
                     <a id="enableWindowLevelTool" class="list-group-item">WW/WC</a>
                     <a id="pan" class="list-group-item">Pan</a>
                     <a id="zoom" class="list-group-item">Zoom</a>
                     <a id="enableLength" class="list-group-item">Length</a>
                     <a id="probe" class="list-group-item">Probe</a>
                     <a id="circleroi" class="list-group-item">Elliptical ROI</a>
                     <a id="rectangleroi" class="list-group-item">Rectangle ROI</a>
                     <a id="angle" class="list-group-item">Angle</a>
                     <a id="highlight" class="list-group-item">Highlight</a>
                     <a id="freehand" class="list-group-item">Freeform ROI</a>
                     <!-- <a id="eraser" class="list-group-item">Eraser</a> @TODO uncomment in 2.3.8 -->
                 </ul>
                 <div class="checkbox">
                     <label><input type="checkbox" id="chkshadow">Apply shadow</label>
                 </div>
                 <br/>
             </div>
         </div>

         <!--// viewer-->
         <div>
             <div style="width:256px;height:256px;position:relative;display:inline-block;color:white;"
                  oncontextmenu="return false"
                  class='cornerstone-enabled-image disable-selection noIbar'
                  unselectable='on'
                  onselectstart='return false;'
                  onmousedown='return false;'>
                 <div id="dicomImage" ref="dicomImage" class="image-responsive"
                      style="top:0px;left:0px; position:absolute;">
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
         <div class="col-xs-12 col-sm-6">
         </div>
         <div class="qr">
             </div>
     </div>
 </div>
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
        name: "patientShow",
        props: ['instances'],

        data() {
            return {
                showLoupe: true,
                instImages: []
            }


        },
        mounted() {

            console.log(this.$props.instances)

            var element = this.$refs.dicomImage;

            // cornerstone.enable(element)
            //
            //
            // function onImageRendered(e) {
            //     var viewport = cornerstone.getViewport(e.target);
            //     document.getElementById('mrbottomleft').textContent = "WW/WC: " + Math.round(viewport.voi.windowWidth) + "/" + Math.round(viewport.voi.windowCenter);
            //     document.getElementById('mrbottomright').textContent = "Zoom: " + viewport.scale.toFixed(2);
            // };
            //
            // element.addEventListener('cornerstoneimagerendered', onImageRendered);
            //
            // document.getElementById('chkshadow').addEventListener('change', function(){
            //     cornerstoneTools.length.setConfiguration({shadow: this.checked});
            //     cornerstoneTools.angle.setConfiguration({shadow: this.checked});
            //     cornerstone.updateImage(element);
            // });
            //
            //
            // var config = {
            //     // invert: true,
            //     minScale: 0.25,
            //     maxScale: 20.0,
            //     preventZoomOutsideImage: true
            // };
            // cornerstoneTools.zoom.setConfiguration(config);
            //
            //
            // cornerstone.loadImage('http://192.168.100.8/api/pat/0b26ee99-2447dd17-7c931f45-d954bb20-839ea5be').then(function(image) {
            //     // Enable mouse and touch input
            //     cornerstoneTools.mouseInput.enable(element);
            //     cornerstoneTools.touchInput.enable(element);
            //
            //     cornerstone.displayImage(element, image);
            //
            //
            //
            //     cornerstone.displayImage(element, image);
            //     cornerstoneTools.mouseInput.enable(element);
            //     cornerstoneTools.mouseWheelInput.enable(element);
            //     // Enable all tools we want to use with this element
            //     cornerstoneTools.wwwc.activate(element, 1); // ww/wc is the default tool for left mouse button
            //     cornerstoneTools.pan.activate(element, 2); // pan is the default tool for middle mouse button
            //     cornerstoneTools.zoom.activate(element, 4); // zoom is the default tool for right mouse button
            //     cornerstoneTools.zoomWheel.activate(element); // zoom is the default tool for middle mouse wheel
            //     cornerstoneTools.probe.enable(element);
            //     cornerstoneTools.length.enable(element);
            //     cornerstoneTools.ellipticalRoi.enable(element);
            //     cornerstoneTools.rectangleRoi.enable(element);
            //     cornerstoneTools.angle.enable(element);
            //     cornerstoneTools.highlight.enable(element);
            //     // cornerstoneTools.eraser.enable(element); Uncomment this with release 2.3.8
            //
            //     activate("enableWindowLevelTool");
            //
            //     function activate(id) {
            //
            //         document.querySelectorAll('a').forEach(function(elem) {
            //             elem.classList.remove('active');
            //         });
            //         document.getElementById(id).classList.add('active');
            //     }
            //     // helper function used by the tool button handlers to disable the active tool
            //     // before making a new tool active
            //     function disableAllTools()
            //     {
            //         cornerstoneTools.wwwc.disable(element);
            //         cornerstoneTools.pan.activate(element, 2); // 2 is middle mouse button
            //         cornerstoneTools.zoom.activate(element, 4); // 4 is right mouse button
            //         cornerstoneTools.probe.deactivate(element, 1);
            //         cornerstoneTools.length.deactivate(element, 1);
            //         cornerstoneTools.ellipticalRoi.deactivate(element, 1);
            //         cornerstoneTools.rectangleRoi.deactivate(element, 1);
            //         cornerstoneTools.angle.deactivate(element, 1);
            //         cornerstoneTools.highlight.deactivate(element, 1);
            //         cornerstoneTools.freehand.deactivate(element, 1);
            //         // cornerstoneTools.eraser.deactivate(element, 1); @TODO: Uncomment this with release 2.3.8
            //     }
            //     // Tool button event handlers that set the new active tool
            //     document.getElementById('enableWindowLevelTool').addEventListener('click', function() {
            //         activate('enableWindowLevelTool')
            //         disableAllTools();
            //         cornerstoneTools.wwwc.activate(element, 1);
            //     });
            //     document.getElementById('pan').addEventListener('click', function() {
            //         activate('pan')
            //         disableAllTools();
            //         cornerstoneTools.pan.activate(element, 3); // 3 means left mouse button and middle mouse button
            //     });
            //     document.getElementById('zoom').addEventListener('click', function() {
            //         activate('zoom')
            //         disableAllTools();
            //         cornerstoneTools.zoom.activate(element, 5); // 5 means left mouse button and right mouse button
            //     });
            //     document.getElementById('enableLength').addEventListener('click', function() {
            //         activate('enableLength')
            //         disableAllTools();
            //         cornerstoneTools.length.activate(element, 1);
            //     });
            //     document.getElementById('probe').addEventListener('click', function() {
            //         activate('probe')
            //         disableAllTools();
            //         cornerstoneTools.probe.activate(element, 1);
            //     });
            //     document.getElementById('circleroi').addEventListener('click', function() {
            //         activate('circleroi')
            //         disableAllTools();
            //         cornerstoneTools.ellipticalRoi.activate(element, 1);
            //     });
            //     document.getElementById('rectangleroi').addEventListener('click', function() {
            //         activate('rectangleroi')
            //         disableAllTools();
            //         cornerstoneTools.rectangleRoi.activate(element, 1);
            //     });
            //     document.getElementById('angle').addEventListener('click', function () {
            //         activate('angle')
            //         disableAllTools();
            //         cornerstoneTools.angle.activate(element, 1);
            //     });
            //     document.getElementById('highlight').addEventListener('click', function() {
            //         activate('highlight')
            //         disableAllTools();
            //         cornerstoneTools.highlight.activate(element, 1);
            //     });
            //     document.getElementById('freehand').addEventListener('click', function() {
            //         activate('freehand')
            //         disableAllTools();
            //         cornerstoneTools.freehand.activate(element, 1);
            //     });
            //
            //     //
            //     // cornerstoneTools.magnify.enable(element);
            //     // cornerstoneTools.magnifyTouchDrag.enable(element);
            //     // cornerstoneTools.pan.activate(element, 2);
            //     // cornerstoneTools.zoom.activate(element, 4);
            //     // // Enable all tools we want to use with this element
            //     // cornerstoneTools.magnify.activate(element, 1);
            //     // cornerstoneTools.magnifyTouchDrag.activate(element);
            //     //
            //     // function activate(id) {
            //     //     document.querySelectorAll('a').forEach(function (elem) {
            //     //         elem.classList.remove('active');
            //     //     });
            //     //     document.getElementById(id).classList.add('active');
            //     // }
            //     //
            //     // activate("activate");
            //     // // Tool button event handlers that set the new active tool
            //     // document.getElementById('disable').addEventListener('click', function () {
            //     //     activate("disable");
            //     //     cornerstoneTools.magnify.disable(element);
            //     //     cornerstoneTools.magnifyTouchDrag.disable(element);
            //     //     return false;
            //     // });
            //     // document.getElementById('activate').addEventListener('click', function () {
            //     //     activate("activate");
            //     //     cornerstoneTools.magnify.activate(element, 1);
            //     //     cornerstoneTools.magnifyTouchDrag.activate(element);
            //     //     return false;
            //     // });
            // });
            // cornerstone.loadAndCacheImage('http://192.168.100.8/api/pat/0b26ee99-2447dd17-7c931f45-d954bb20-839ea5be').then(function(image) {
            //     cornerstone.displayImage(element, image);
            //     // We can now set some viewport parameters
            //     // and use them to update the element
            //     var viewport = {
            //         invert: false,
            //         pixelReplication: false,
            //         voi: {
            //             windowWidth: 400,
            //             windowCenter: 200
            //         },
            //         scale: 1.4,
            //         translation: {
            //             x: 0,
            //             y: 0
            //         },
            //         //colormap: 'hot'
            //     };
            //
            //     cornerstone.setViewport(element, viewport);
            //     cornerstone.updateImage(element);
            //     cornerstoneTools.mouseInput.enable(element);
            //     cornerstoneTools.mouseWheelInput.enable(element);
            //     cornerstoneTools.wwwc.activate(element, 1); // ww/wc is the default tool for left mouse button
            //     cornerstoneTools.pan.activate(element, 2); // pan is the default tool for middle mouse button
            //     cornerstoneTools.zoom.activate(element, 4); // zoom is the default tool for right mouse button
            //     cornerstoneTools.zoomWheel.activate(element); // zoom is the default tool for middle mouse wheel
            //
            // });
            //


        },
        methods: {
            getImage(id) {
                return axios.get('/api/pat/' + id , { responseType: 'arraybuffer' })
                    .then((response) => {
                        let image = btoa(
                            new Uint8Array(response.data)
                                .reduce((data, byte) => data + String.fromCharCode(byte), '')
                        );
                        return `data:${response.headers['content-type'].toLowerCase()};base64,${image}`;
                    });
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