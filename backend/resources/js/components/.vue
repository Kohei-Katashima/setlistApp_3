<template>
  <div>
    <draggable tag="tbody" v-model="items">
      <tr v-for="(song) in songs" :key="song.id">
        {{
          song.title
        }}-(No.{{
          song.id
        }})
      </tr>
    </draggable>
  </div>
</template>

<script>
import draggable from "vuedraggable";
const Storage = window.VueStorage;
Vue.use(Storage);

export default {
  components: {
    draggable,
  },
  data: {
    songs: [],
  },
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
    doReload: function () {
      // ページのリロード
      location.reload();
    },
    doSessionClear: function () {
      // ブラウザストレージを削除する
      if (confirm("セッションを削除します")) {
        Vue.ls.clear();
      }
    },
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
