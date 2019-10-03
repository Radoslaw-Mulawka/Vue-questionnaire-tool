import Resource from './resource';
import request from '@/utils/request';

class Questions extends Resource {
  copy(id) {
    return request({
      url: '/' + this.uri + '/' + id + '/copy',
      method: 'get',
    });
  };
}

export default Questions;
