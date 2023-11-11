import { currentUrlParams, reloadWithQuery, reloadWithoutQueryParams } from '@/utils/route';
import { produce } from "immer";
import isArray from 'lodash/isArray';
import { create } from 'zustand';

const params = currentUrlParams();

const useTaskFiltersStore = create((set, get) => ({
  filters: {
    groups: params.groups || [],
    assignees: params.assignees || [],
    due_date: {
      not_set: params.not_set || 0,
      overdue: params.overdue || 0,
    },
    labels: params.labels || [],
  },
  hasUrlParams: () => {
    return Object.keys(currentUrlParams()).length > 0;
  },
  hasFilters: () => {
    const filters = get().filters;
    const keys = Object.keys(filters);

    return keys.some((key) => {
      if (isArray(filters[key])) {
        return filters[key].length > 0;
      } else {
        const keys = Object.keys(filters[key]);
        return keys.some((k) => !!filters[key][k]);
      }
    });
  },
  clearFilters: () => {
    reloadWithoutQueryParams();

    return set(() => ({
      filters: {
        groups: [],
        assignees: [],
        due_date: {
          not_set: 0,
          overdue: 0,
        },
        labels: [],
      }
    }));
  },
  toggleArrayFilter: (field, id) => {
    return set(
      produce((state) => {
        const index = state.filters[field].findIndex((i) => i === id);

        if (index !== -1) {
          state.filters[field].splice(index, 1);
        } else {
          state.filters[field].push(id);
        }
        reloadWithQuery({ [field]: state.filters[field] });
      }),
    );
  },
  toggleObjectFilter: (field, property) => {
    return set(
      produce((state) => {
        if (state.filters[field][property] === 0) {
          state.filters[field][property] = 1;
          reloadWithQuery({ [property]: 1 });
        } else {
          state.filters[field][property] = 0;
          reloadWithoutQueryParams([property]);
        }
      }),
    );
  },
}));

export default useTaskFiltersStore;
