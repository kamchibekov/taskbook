let changeStatus = async (target) => {
  let task_id = target.getAttribute("task-id");
  let formData = new FormData();

  formData.append("task_id", parseInt(task_id));
  formData.append("status", target.checked ? 1 : 0);

  let response = await fetch("/task/change", {
    method: "POST",
    body: formData,
    redirect: "follow",
  });
  if (response.redirected) {
    location = response.url;
  }
};

let changeText = async (target) => {
  let task_id = target.getAttribute("task-id");
  let formData = new FormData();

  formData.append("task_id", parseInt(task_id));
  formData.append("text", target.value);

  let response = await fetch("/task/change", {
    method: "POST",
    body: formData,
    redirect: "follow",
  });
  if (response.redirected) {
    location = response.url;
  }
};
