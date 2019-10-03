import request from '@/utils/request';
import Campaigns from '@/api/campaigns';
import Questions from '@/api/questions';
import Resource from '@/api/resource';
// TODO: api na zmianę wszystkiego na blur, radio + checkbox 1 defaultowa opcja, nie można usunąć opcję jeśli jest ona 1 opcją pytania,
const apiCampaign = new Campaigns('campaigns');
const apiQuestions = new Questions('questions');
const apiOptions = new Resource('options');
const apiAnswers = new Resource('answers');

const state = {
  campaignsListData: [],
  campaignData: {
    id: null,
    dateFrom: null,
    dateTo: null,
    name: '',
    banner: '',
    enterText: '',
    questionsList: [
      // {
      //   id: 123,
      //   type: 'checkbox',
      //   required: true,
      //   questionMainText: 'checkbox text test',
      //   questionAdditionalText: 'additional text test',
      //   options: [{
      //     id: 1,
      //     optionText: 'option text',
      //   }],
      // },
      // {
      //   // id: 123,
      //   type: 'checkbox',
      //   required: true,
      //   questionMainText: 'checkbox text test',
      //   questionAdditionalText: 'additional text test',
      //   options: [{
      //   //   id: 1,
      //     optionText: 'option text',
      //   }],
      // },
    ],
    endText: '',
  },
  campaignAnswersData: null,
  questionLoader: {
    isLoading: false,
    type: '',
  },
  questionIsBeingChanged: {
    isBeingChanged: false,
    questionId: null,
  },
};

const mutations = {
  SET_CAMPAIGNS_LIST: (state, campaignsListData) => {
    state.campaignsListData = campaignsListData;
  },
  SET_CAMPAIGN_NAME: (state, name) => {
    state.campaignData.name = name;
  },
  SET_CAMPAIGN_ENTER_TEXT: (state, enterText) => {
    state.campaignData.enterText = enterText;
  },
  SET_CAMPAIGN_END_TEXT: (state, endText) => {
    state.campaignData.endText = endText;
  },
  SET_CAMPAIGN_BANNER: (state, image) => {
    state.campaignData.banner = image[0];
  },
  ADD_CAMPAIGN_QUESTION: (state, data) => {
    const questionList = [...state.campaignData.questionsList];
    for (const key in data.data) {
      if (key === 'options') {
        continue;
      }
      data.data[`_${key}`] = data.data[key];
      Object.defineProperty(data.data, `${key}`, {
        get: function() {
          return data.data[`_${key}`];
        },
        set: function(newValue) {
          data.data[`_${key}`] = newValue;
          state.questionIsBeingChanged = {
            isBeingChanged: true,
            questionId: data.data.id,
          };
          apiQuestions.update(data.data.id, {
            [key]: newValue,
          }).then(data => {
            state.questionIsBeingChanged = {
              isBeingChanged: false,
              questionId: null,
            };
          }).catch(e => {
            state.questionIsBeingChanged = {
              isBeingChanged: false,
              questionId: null,
            };
          });
        },
        enumerable: true,
        configurable: true,
      });
    }
    questionList.push(data.data);
    state.campaignData.questionsList = [...questionList];
    state.questionLoader = {
      isLoading: false,
      type: '',
    };
  },
  SET_QUESTION_REQUIRED_FIELD: (state, { isRequired, localQuestionId, id }) => {
    const questionList = [...state.campaignData.questionsList];
    if (localQuestionId !== null) {
      questionList[localQuestionId].required = isRequired;
    } else {
      questionList.find((item, index) => {
        if (item.id === id) {
          questionList[index].required = isRequired;
        }
      });
    }
    state.campaignData.questionsList = [...questionList];
  },
  DELETE_QUESTION: (state, { localQuestionId, id }) => {
    const questionList = [...state.campaignData.questionsList];
    questionList.splice(questionList.indexOf(questionList.find(item => item.id === id)), 1);
    state.campaignData.questionsList = [...questionList];
  },
  DELETE_OPTION: (state, { questionId, optionId }) => {
    const questionList = [...state.campaignData.questionsList];
    const question = questionList.find((item) => item.id === questionId);

    question.options = [...question.options.filter(option => {
      return option.id !== optionId;
    })];

    state.campaignData.questionsList = [...questionList];
  },
  COPY_QUESTION: (state, { id, data }) => {
    const questionList = [...state.campaignData.questionsList];
    for (const key in data.data) {
      if (key === 'options') {
        continue;
      }
      data.data[`_${key}`] = data.data[key];
      Object.defineProperty(data.data, `${key}`, {
        get: function() {
          return data.data[`_${key}`];
        },
        set: function(newValue) {
          data.data[`_${key}`] = newValue;
          state.questionIsBeingChanged = {
            isBeingChanged: true,
            questionId: data.data.id,
          };
          apiQuestions.update(data.data.id, {
            [key]: newValue,
          }).then(data => {
            state.questionIsBeingChanged = {
              isBeingChanged: false,
              questionId: null,
            };
          }).catch(e => {
            state.questionIsBeingChanged = {
              isBeingChanged: false,
              questionId: null,
            };
          });
        },
        enumerable: true,
        configurable: true,
      });
    };

    questionList.push(data.data);

    state.campaignData.questionsList = [...questionList];
  },
  SET_MAIN_TEXT: (state, { mainText, localQuestionId, id }) => {
    const questionList = [...state.campaignData.questionsList];
    if (localQuestionId !== null) {
      questionList.find((item, index) => index === localQuestionId).questionMainText = mainText;
    } else {
      questionList.find((item) => item.id === id).questionMainText = mainText;
    }
    state.campaignData.questionsList = [...questionList];
  },
  SET_ADDITIONAL_TEXT: (state, { additionalText, localQuestionId, id }) => {
    const questionList = [...state.campaignData.questionsList];
    if (localQuestionId !== null) {
      questionList.find((item, index) => index === localQuestionId).questionAdditionalText = additionalText;
    } else {
      questionList.find((item) => item.id === id).questionAdditionalText = additionalText;
    }
    state.campaignData.questionsList = [...questionList];
  },
  ADD_OPTION: (state, { id, data }) => {
    const questionList = [...state.campaignData.questionsList];
    questionList.find(item => item.id === id).options.push(data.data);
    state.campaignData.questionsList = [...questionList];
  },
  CHANGE_OPTION_TEXT: (state, { optionText, questionId, optionId }) => {
    const questionList = [...state.campaignData.questionsList];
    questionList.find((item, index) => item.id === questionId).options.find(option => option.id === optionId).optionText = optionText;
    state.campaignData.questionsList = [...questionList];
  },
  CLEAR_CAMPAIGN_DATA: (state) => {
    state.campaignData = {
      // id: 0,
      // dateFrom: '',
      // dateTo: '',
      name: '',
      banner: '',
      enterText: '',
      questionsList: [
        // {
        //   // id: 123,
        //   type: 'checkbox',
        //   required: true,
        //   questionMainText: 'checkbox text test',
        //   questionAdditionalText: 'additional text test',
        //   options: [{
        //   //   id: 1,
        //     optionText: 'option text',
        //   }],
        // },
      ],
      endText: '',
    };
  },
  REMOVE_BANNER: (state) => {
    state.campaignData.banner = '';
  },
  GET_CAMPAIGN_DATA: (state, data) => {
    for (const question of data.questionsList) {
      for (const key in question) {
        if (key === 'options') {
          continue;
        }
        question[`_${key}`] = question[key];
        Object.defineProperty(question, `${key}`, {
          get: function() {
            return question[`_${key}`];
          },
          set: function(newValue) {
            question[`_${key}`] = newValue;
            state.questionIsBeingChanged = {
              isBeingChanged: true,
              questionId: question.id,
            };
            apiQuestions.update(question.id, {
              [key]: newValue,
            }).then(data => {
              state.questionIsBeingChanged = {
                isBeingChanged: false,
                questionId: null,
              };
            }).catch(e => {
              state.questionIsBeingChanged = {
                isBeingChanged: false,
                questionId: null,
              };
            });
          },
          enumerable: true,
          configurable: true,
        });
      }
    }
    state.campaignData = data;
  },
  SET_CAMPAIGN_STATUS: (state, status) => {
    state.campaignData.status = status;
  },
  DELETE_CAMPAIGN: (state, campaignId) => {
    // // ????
    state.campaignsListData = [...state.campaignsListData].filter(item => item.id !== campaignId);
  },
  SET_CAMPAIGN_ANSWERS: (state, campaignAnswersData) => {
    state.campaignAnswersData = campaignAnswersData;
  },
  SET_CAMPAIGN_LIST: (state, campaignList) => {
    state.campaignsListData = campaignList;
  },
};

const actions = {
  getCampaignsList({ commit }) {
    request({
      url: '/campaigns',
      method: 'get',
    })
      .then(response => {
        commit('SET_CAMPAIGNS_LIST', response.data);
      })
      .catch((error) => {
        console.error('Problem in getting campaigns\' list data', error);
      });
  },
  changeCampaignName({ commit }, name) {
    return new Promise((resolve, reject) => {
      apiCampaign.update(state.campaignData.id, { 'name': name }).then(() => {
        commit('SET_CAMPAIGN_NAME', name);
        resolve();
      }).catch(error => {
        console.log(error);
        reject(error);
      });
    });
  },
  changeCampaignNameCampaignCreation({ commit }, name) {
    commit('SET_CAMPAIGN_NAME', name);
  },
  changeCampaignEnterText({ commit }, enterText) {
    return new Promise((resolve, reject) => {
      apiCampaign.update(state.campaignData.id, { 'enterText': enterText }).then(() => {
        commit('SET_CAMPAIGN_ENTER_TEXT', enterText);
        resolve();
      }).catch(error => {
        console.log(error);
        reject(error);
      });
    });
  },
  changeCampaignEndText({ commit }, endText) {
    return new Promise((resolve, reject) => {
      apiCampaign.update(state.campaignData.id, { 'endText': endText }).then(() => {
        commit('SET_CAMPAIGN_END_TEXT', endText);
        resolve();
      }).catch(error => {
        console.log(error);
        reject(error);
      });
    });
  },
  changeCampaignBanner({ commit }, formData) {
    return new Promise((resolve, reject) => {
      apiCampaign.storeImage(state.campaignData.id, formData).then((response) => {
        commit('SET_CAMPAIGN_BANNER', response.data);
        resolve();
      }).catch(error => {
        console.log(error);
        reject();
      });
    });
  },
  addCampaignQuestion({ commit }, type) {
    if (state.campaignData.status !== 1) {
      state.questionLoader = {
        isLoading: true,
        type: type,
      };
      apiQuestions.store({
        type: type,
        campaignsId: state.campaignData.id,
      }).then(data => {
        commit('ADD_CAMPAIGN_QUESTION', data);
      });
    }
  },
  setQuestionRequiredField({ commit }, { isRequired, localQuestionId, id }) {
    commit('SET_QUESTION_REQUIRED_FIELD', { isRequired, localQuestionId, id });
  },
  deleteQuestion({ commit }, { id }) {
    apiQuestions.destroy(id).then(() => {
      commit('DELETE_QUESTION', { id });
    }).catch(error => {
      console.error(error);
    });
  },
  copyQuestion({ commit }, { id }) {
    apiQuestions.copy(id).then((data) => {
      commit('COPY_QUESTION', { id, data });
    }).catch(error => {
      console.error(error);
    });
  },
  changeMainText({ commit }, { mainText, localQuestionId, id }) {
    commit('SET_MAIN_TEXT', { mainText, localQuestionId, id });
  },
  changeAdditionalText({ commit }, { additionalText, localQuestionId, id }) {
    commit('SET_ADDITIONAL_TEXT', { additionalText, localQuestionId, id });
  },
  addOption({ commit }, { id }) {
    apiOptions.store({
      questionId: id,
    }).then(data => {
      commit('ADD_OPTION', { id, data });
    }).catch(error => {
      console.error(error);
    });
  },
  changeOptionText({ commit }, { optionText, questionId, optionId }) {
    apiOptions.update(optionId, { label: optionText }).then((data) => {
      commit('CHANGE_OPTION_TEXT', { optionText, questionId, optionId });
    }).catch(error => {
      console.error(error);
    });
  },
  deleteOption({ commit }, { questionId, optionId }) {
    apiOptions.destroy(optionId).then(() => {
      commit('DELETE_OPTION', { questionId, optionId });
    }).catch(error => {
      console.error(error);
    });
  },
  clearCampaignData({ commit }) {
    commit('CLEAR_CAMPAIGN_DATA');
  },
  removeBanner({ commit }) {
    commit('REMOVE_BANNER');
  },
  getCampaignData({ commit }, id) {
    return new Promise((resolve, reject) => {
      apiCampaign.get(id).then(({ data }) => {
        async function imageImport() {
          const myImage = await import(`../../assets/login_page/town-login.png`);
          data.banner = myImage['default'];
        }
        if (data.banner == null) {
          imageImport().then(() => {
            commit('GET_CAMPAIGN_DATA', data);
            resolve('success');
          });
        } else {
          data.banner = `${data.banner}`;
          commit('GET_CAMPAIGN_DATA', data);
          resolve('success');
        }
      }).catch(error => {
        reject('Error', error);
      });
    });
  },
  changeStatus({ commit }, { status }) {
    apiCampaign.changeStatus(state.campaignData.id, status).then(() => {
      commit('SET_CAMPAIGN_STATUS', status);
    });
  },
  deleteCampaign({ commit }, campaignId) {
    return new Promise((resolve, reject) => {
      apiCampaign.destroy(campaignId).then(response => {
        commit('DELETE_CAMPAIGN', campaignId);
        resolve();
      }).catch(error => {
        console.error(error);
        reject(error);
      });
    });
  },
  getCampaignAnswers({ commit }, campaignId) {
    return new Promise((resolve, reject) => {
      apiAnswers.get(campaignId).then(data => {
        resolve();
        commit('SET_CAMPAIGN_ANSWERS', data.data);
      }).catch(error => {
        reject(error);
      });
    });
  },
  setCampaignList({ commit }, campaignList) {
    commit('SET_CAMPAIGN_LIST', campaignList);
  },
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
};
