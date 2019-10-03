import Resource from './resource';
import request from '@/utils/request';

class Campaigns extends Resource {
  // constructor() {
  //   super('campaigns');
  // }
  changeStatus(id, status) {
    return request({
      url: '/' + this.uri + '/' + id + '/status',
      method: 'put',
      data: {
        status: status,
      },
    });
  }
  storeImage(id, resource) {
    return request({
      url: '/' + this.uri + '/' + id + '/banner',
      method: 'post',
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      data: resource,
    });
  }
}

export default Campaigns;
