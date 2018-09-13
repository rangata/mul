<template>
    <div class="container">
        {{ image }}
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
        name: "imageStudio",
        props: ['image-id'],
        data() {
            return {
                // imageId: {}
                image: {}
            }
        },
        mounted() {
           this.image = this.getImage(this.$props.imageId)
        },
        methods: {
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
            }
        }
    }
</script>

<style scoped>

</style>