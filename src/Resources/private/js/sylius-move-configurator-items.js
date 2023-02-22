import * as api from 'semantic-ui-css/components/api';
import * as $ from 'jquery'

$.fn.extend({
  moveConfiguratorItems(positionInput) {
    const configuratorItemRows = [];
    const element = this;

    element.api({
      method: 'PUT',
      beforeSend(settings) {
        /* eslint-disable-next-line no-param-reassign */
        settings.data = {
          configuratorItems: configuratorItemRows,
          _csrf_token: element.data('csrf-token'),
        };

        return settings;
      },
      onSuccess() {
        window.location.reload();
      },
    });

    positionInput.on('input', (event) => {
      const input = $(event.currentTarget);
      const configuratorItemId = input.data('id');
      const row = configuratorItemRows.find(({ id }) => id === configuratorItemId);

      if (!row) {
        configuratorItemRows.push({
          id: configuratorItemId,
          position: input.val(),
        });
      } else {
        row.position = input.val();
      }
    });
  },
});
