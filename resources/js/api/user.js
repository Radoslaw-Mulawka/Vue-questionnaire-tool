// import request from '@/utils/request';
import Resource from '@/api/resource';
import request from '@/utils/request';
class UserResource extends Resource {
  constructor() {
    super('users');
  }
  updateProfile(resource) {
    return request({
      url: '/profile',
      method: 'put',
      data: resource,
    });
  }
  changePassword(resource) {
    return request({
      url: '/profile/changepassword',
      method: 'put',
      data: resource,
    });
  }
  storeImage(resource) {
    return request({
      url: '/profile/banner',
      method: 'post',
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      data: resource,
    });
  }
  deleteImage() {
    return request({
      url: '/profile/banner',
      method: 'delete',
    });
  }
  deleteProfile() {
    return request({
      url: '/profile',
      method: 'delete',
    });
  }
}

export { UserResource as default };
