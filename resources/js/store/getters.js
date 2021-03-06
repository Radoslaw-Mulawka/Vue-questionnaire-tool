const getters = {
  sidebar: state => state.app.sidebar,
  language: state => state.app.language,
  size: state => state.app.size,
  device: state => state.app.device,
  visitedViews: state => state.tagsView.visitedViews,
  cachedViews: state => state.tagsView.cachedViews,
  userId: state => state.user.id,
  token: state => state.user.token,
  avatar: state => state.user.avatar,
  name: state => state.user.name,
  introduction: state => state.user.introduction,
  roles: state => state.user.roles,
  permissions: state => state.user.permissions,
  permission_routes: state => state.permission.routes,
  addRoutes: state => state.permission.addRoutes,
  getCampaignsList: state => state.campaign.campaignsListData,
  getCampaignData: state => state.campaign.campaignData,
  getUsersList: state => state.user.usersListData,
  getQuestionLoader: state => state.campaign.questionLoader,
  getCampaignAnswersData: state => state.campaign.campaignAnswersData,
  questionIsBeingChanged: state => state.campaign.questionIsBeingChanged,
  getUserData: state => state.user,
};
export default getters;
