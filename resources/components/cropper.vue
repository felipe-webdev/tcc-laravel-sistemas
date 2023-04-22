<template>
<div class="container-lg hstack gap-3">
  <!-- <div
    class="model"
    v-show="model"
    @click="model = false"
  >
    <div class="model-show">
      <img :src="modelSrc" alt="">
    </div>
  </div> -->

  <div>
    <div style="width: 500px; height: 500px;">
      <vue-cropper
        ref="cropper"
        :img="option.img"
        :mode="option.mode"
        :max-img-size="option.maxImgSize"
        :info="option.info"
        :info-true="option.infoTrue"
        :original="option.original"
        :output-type="option.outputType"
        :output-size="option.outputSize"
        :full="option.full"
        :high="option.high"
        :can-scale="option.canScale"
        :can-move="option.canMove"
        :can-move-box="option.canMoveBox"
        :center-box="option.centerBox"
        :fixed-box="option.fixedBox"
        :fixed="option.fixed"
        :fixed-number="option.fixedNumber"
        :limit-min-size="option.limitMinSize"
        :auto-crop="option.autoCrop"
        :auto-crop-width="option.autoCropWidth"
        :auto-crop-height="option.autoCropHeight"
      />
        <!-- @real-time="realTime" -->
        <!-- @img-load="imgLoad" -->
        <!-- @crop-moving="cropMoving" -->
    </div>

    <div class="hstack justify-content-center gap-3 mt-3">
      <label class="btn btn-info btn-sm" for="uploads">
        <i class="fa-solid fa-folder-open"></i>
        Selecionar arquivo
      </label>
      <input
        type="file"
        id="uploads"
        style="position:absolute;clip:rect(0 0 0 0);"
        accept="image/png, image/jpeg, image/gif, image/jpg"
        @change="uploadImg($event, 1)"
      />

      <button
        class="btn btn-info btn-sm"
        @click="rotateLeft"
      >
        <i class="fa-solid fa-rotate-left"></i>
        Girar
      </button>

      <button
        class="btn btn-info btn-sm"
        @click="rotateRight"
      >
        <i class="fa-solid fa-rotate-right"></i>
        Girar
      </button>

      <button
        class="btn btn-orange btn-sm"
        @click="finish('blob')"
      >
        <i class="fa-solid fa-check"></i>
        Confirmar
      </button>
    </div>
  </div>

  <div class="vstack justify-content-center text-light">
    <h1 class="fs-3">Instruções:</h1>
    <p>A imagem final será o conteúdo da área iluminada.</p>
    <p>Centralize o rosto na área iluminada.</p>
    <p>Utilize o zoom com a roda do mouse.</p>
    <p>Clique e arraste para mover a imagem.</p>

    <!-- <div
      class="show-preview"
      :style="{
        'width': previews.w + 'px',
        'height': previews.h + 'px',
        'overflow': 'hidden',
        'margin': '5px'
      }"
    >
      <div :style="previews.div">
        <img :src="previews.url" :style="previews.img">
      </div>
    </div> -->
  </div>
</div>
</template>

<script>
  export default {
    components: {},
    directives: {},
    props: [],
    data() {
      return {
        model: false,
        modelSrc: '',
        crap: false,
        previews: {},
        option: {
          img: '',
          mode: 'cover',
          maxImgSize: 2000,
          info: false,
          infoTrue: true,
          original: false,
          outputType: 'png',
          outputSize: 1,
          full: false,
          high: false,
          canScale: true,
          canMove: true,
          canMoveBox: false,
          centerBox: true,
          fixedBox: true,
          fixed: true,
          fixedNumber: [1, 1],
          limitMinSize: [400, 400],
          autoCrop: true,
          autoCropWidth: 400,
          autoCropHeight: 400,
        },
        show: true,
      }
    },
    computed: {},
    watch: {},
    created(){},
    mounted(){},
    updated(){},
    activated(){},
    deactivated(){},
    methods: {
      // startCrop() {
      //   // start
      //   this.crap = true
      //   this.$refs.cropper.startCrop()
      // },

      // stopCrop() {
      //   //  stop
      //   this.crap = false
      //   this.$refs.cropper.stopCrop()
      // },

      // clearCrop() {
      //   // clear
      //   this.$refs.cropper.clearCrop()
      // },

      // refreshCrop() {
      //   // clear
      //   this.$refs.cropper.refresh()
      // },

      // changeScale(num) {
      //   num = num || 1
      //   this.$refs.cropper.changeScale(num)
      // },

      rotateLeft() {
        this.$refs.cropper.rotateLeft()
      },

      rotateRight() {
        this.$refs.cropper.rotateRight()
      },

      finish(type) {
        // Output
        if (type === 'blob') {
          this.$refs.cropper.getCropBlob((data) => {
            this.$emit('blob', data)
            var img = window.URL.createObjectURL(data)
            console.log(img)
            this.model = true
            this.modelSrc = img
          })
        } else {
          this.$refs.cropper.getCropData((data) => {
            this.model = true
            this.modelSrc = data
          })
        }
      },

      // down(type) {
      //   // event.preventDefault()
      //   var aLink = document.createElement('a')
      //   aLink.download = 'demo'
      //   // Output
      //   if (type === 'blob') {
      //     this.$refs.cropper.getCropBlob((data) => {
      //       this.downImg = window.URL.createObjectURL(data)
      //       aLink.href = window.URL.createObjectURL(data)
      //       aLink.click()
      //     })
      //   } else {
      //     this.$refs.cropper.getCropData((data) => {
      //       this.downImg = data
      //       aLink.href = data
      //       aLink.click()
      //     })
      //   }
      // },

      uploadImg(e, num) {
        // UPLOAD IMAGE
        var file = e.target.files[0]
        if (!/\.(gif|jpg|jpeg|png|bmp|GIF|JPG|PNG)$/.test(e.target.value)) {
          alert('The image type must be one of .gif, .jpeg, .jpg, .png, or .bmp')
          return false
        }
        var reader = new FileReader()
        reader.onload = (e) => {
          let data
          if (typeof e.target.result === 'object') {
            // CONVERT ARRAY BUFFER TO BLOB. NO NEED TO DO THIS IF IT IS IN BASE64
            data = window.URL.createObjectURL(new Blob([e.target.result]))
          } else {
            data = e.target.result
          }
          if (num === 1) {
            this.option.img = data
          } else if (num === 2) {
            this.example2.img = data
          }
        }
        // CONVERT TO BASE64
        // reader.readAsDataURL(file)
        // CONVERT TO BLOB
        reader.readAsArrayBuffer(file)
      },

      // REAL-TIME PREVIEW FUNCTION
      // realTime(data) {
      //   this.previews = data
      //   console.log(data)
      // },

      // imgLoad(msg) {
      //   console.log(msg)
      // },

      // cropMoving(data) {
      //   console.log(data, 'The current coordinates of the screenshot box')
      // },
    },
  }
</script>

<style scoped>
/* .model {
  position: fixed;
  z-index: 10;
  width: 100vw;
  height: 100vh;
  overflow: auto;
  top: 0;
  left: 0;
  background: rgba(0, 0, 0, 0.8);
}

.model-show {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100vw;
  height: 100vh;
}

.model img {
  display: block;
  margin: auto;
  max-width: 80%;
  user-select: none;
  background-position: 0px 0px, 10px 10px;
  background-size: 20px 20px;
  background-image: linear-gradient(45deg, #eee 25%, transparent 25%, transparent 75%, #eee 75%, #eee 100%),linear-gradient(45deg, #eee 25%, white 25%, white 75%, #eee 75%, #eee 100%);
} */
</style>