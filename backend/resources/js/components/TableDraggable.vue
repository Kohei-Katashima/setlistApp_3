<template>
  <table class="table">
    <thead>
      <tr>
        <th class="text-nowrap">曲順</th>
        <th>タイトル</th>
        <th>
          <div class="text-center">アーティスト名</div>
        </th>
        <th>時間</th>
        <th>メモ</th>
        <th></th>
      </tr>
    </thead>
    <draggable
      :list="songsNew"
      :options="{ animation: 200 }"
      :element="'tbody'"
      @change="update"
    >
      <tr v-for="(song, index) in songsNew" :key="index">
        <td>{{ index + 1 }}</td>
        <td>{{ song.title }}</td>
        <td>
          <div class="text-center">
            {{ song.band_name }}
          </div>
        </td>
        <td>{{ song.time | moment(5) }}</td>
        <td class="text-nowrap">{{ song.memo }}</td>
        <td>
          <div class="ml-auto card-text">
            <div class="dropdown">
              <a
                data-toggle="dropdown"
                aria-haspopup="true"
                aria-expanded="false"
              >
                <button type="button" class="btn btn-link text-muted m-0 p-2">
                  <i class="fas fa-ellipsis-v"></i>
                </button>
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" :href="'songs/' + song.id + '/edit'">
                  <i class="fas fa-pen mr-1"></i>曲を編集する
                </a>
                <div class="dropdown-divider"></div>
                <form
                  :action="'songs/' + song.id + '/delete'"
                  method="POST"
                  v-on:click="checkDelete()"
                >
                  <input type="hidden" name="_token" :value="csrf" />
                  <input type="hidden" name="_method" value="delete" />
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="fas fa-trash-alt mr-1"></i>曲を削除する
                  </button>
                </form>
              </div>
            </div>
          </div>
        </td>
      </tr>
    </draggable>
  </table>
</template>

<script>
import draggable from "vuedraggable";
export default {
  components: {
    draggable,
  },
  props: ["songs"],
  data() {
    return {
      songsNew: this.songs,
      csrf: document.head.querySelector('meta[name="csrf-token"]').content,
    };
  },
  methods: {
    update() {
      this.songsNew.map((song, index) => {
        song.order = index + 1;
      });
      axios
        .put("songs/update", {
          songs: this.songsNew,
        })
        .then((response) => {
          // success message
        });
    },
    checkDelete: function () {
      if (window.confirm("削除してよろしいですか？")) {
        return true;
      } else {
        return false;
      }
    },
  },
  mounted() {
    console.log("Component mounted.");
  },
  filters: {
    moment: function (value, size) {
      return value.substr(0, size);
    },
  },
};
</script>
