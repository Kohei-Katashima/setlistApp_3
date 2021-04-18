<template>
  <div>
    <draggable tag="ul" v-model="songs">
      <li v-for="(song, index) in getSongs" :key="index">
        {{
          song.title
        }}-(No.{{
          song.id
        }})
      </li>
    </draggable>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import Storage from 'vue-ls';
const Storage = window.VueStorage;
Vue.use(Storage);

export default {
  components: {
    draggable,
    Storage,
  },
  props: ["songs"],
  beforeMount: function () {
    if (Vue.ls.get("lsValue")) {
      // ブラウザストレージデータがある場合
      this.songs = JSON.parse(Vue.ls.get("lsValue"));
    } else {
      this.songs = [
      ];
    }
  },
  methods: {
    
  },
  computed: {
    getSongs: {
      get: function () {
        return this.songs;
      },
      set: function (value) {
        this.songs = value;
      },
    },
  },
  watch: {
    songs: function (value) {
      //itemsが更新される度にローカルストレージを更新
      Vue.ls.set("lsValue", JSON.stringify(value), 60 * 60 * 1000);
    },
  },
};

</script>
